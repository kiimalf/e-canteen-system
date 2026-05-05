<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Handle Midtrans payment notification (webhook).
     */
    public function callback(Request $request)
    {
        // Set Midtrans server key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        try {
            $notification = new \Midtrans\Notification();

            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $paymentType = $notification->payment_type;
            $fraudStatus = $notification->fraud_status ?? null;

            // Extract idpesanan from order_id format: KANTIN-{id}-{timestamp}
            $parts = explode('-', $orderId);
            $idpesanan = $parts[1] ?? null;

            if (!$idpesanan) {
                return response()->json(['message' => 'Invalid order ID'], 400);
            }

            $pesanan = Pesanan::find($idpesanan);
            if (!$pesanan) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Determine payment method
            $metodeBayar = 'VA';
            if (in_array($paymentType, ['gopay', 'shopeepay', 'qris'])) {
                $metodeBayar = 'QRIS';
            }

            // Update or create payment record
            $payment = Payment::updateOrCreate(
                ['idpesanan' => $idpesanan],
                [
                    'metode_bayar' => $metodeBayar,
                    'transaction_id' => $notification->transaction_id ?? null,
                    'status' => $transactionStatus,
                ]
            );

            // Update pesanan status based on transaction status
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                if ($fraudStatus == 'accept' || $fraudStatus === null) {
                    $pesanan->update(['status' => 'lunas']);
                }
            } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $payment->update(['status' => $transactionStatus == 'deny' ? 'cancel' : $transactionStatus]);
            }

            Log::info('Midtrans Callback', [
                'order_id' => $orderId,
                'status' => $transactionStatus,
                'payment_type' => $paymentType,
            ]);

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Manual payment confirmation (for demo/testing without Midtrans).
     * Updates order status to lunas directly.
     */
    public function manualConfirm(Request $request, $idpesanan)
    {
        $pesanan = Pesanan::findOrFail($idpesanan);

        $metodeBayar = $request->input('metode_bayar', 'VA');

        Payment::updateOrCreate(
            ['idpesanan' => $idpesanan],
            [
                'metode_bayar' => $metodeBayar,
                'transaction_id' => 'MANUAL-' . time(),
                'status' => 'settlement',
            ]
        );

        $pesanan->update(['status' => 'lunas']);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dikonfirmasi!',
        ]);
    }
}
