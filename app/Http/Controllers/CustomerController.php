<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Customer;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display the POS ordering page.
     */
    public function index()
    {
        $vendors = Vendor::all();
        return view('customer.index', compact('vendors'));
    }

    /**
     * Get all vendors for Select2 (API).
     */
    public function getVendors()
    {
        $vendors = Vendor::all(['idvendor', 'nama_vendor']);
        return response()->json($vendors);
    }

    /**
     * Get menu items by vendor (API for cascading select).
     */
    public function getMenuByVendor($idvendor)
    {
        $menus = Menu::where('idvendor', $idvendor)
            ->where('stok', '>', 0)
            ->get();
        return response()->json($menus);
    }

    /**
     * Store a new order.
     * Creates customer (auto ID via trigger), pesanan, and detail_pesanan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idvendor' => 'required|exists:vendor,idvendor',
            'items' => 'required|array|min:1',
            'items.*.idmenu' => 'required|exists:menu,idmenu',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.catatan' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Create customer (trigger auto-generates idcustomer)
            // We pass a placeholder; the trigger will overwrite it
            DB::statement("INSERT INTO customer (idcustomer, nama) VALUES ('temp', 'Guest')");
            $customer = Customer::orderBy('idcustomer', 'desc')->first();

            $total = 0;
            $itemDetails = [];

            foreach ($request->items as $item) {
                $menu = Menu::findOrFail($item['idmenu']);

                // Check stock
                if ($menu->stok < $item['jumlah']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$menu->nama_menu} tidak mencukupi. Sisa: {$menu->stok}"
                    ], 422);
                }

                $subtotal = $menu->harga * $item['jumlah'];
                $total += $subtotal;

                $itemDetails[] = [
                    'idmenu' => $item['idmenu'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $menu->harga,
                    'subtotal' => $subtotal,
                    'catatan' => $item['catatan'] ?? null,
                ];

                // Reduce stock
                $menu->decrement('stok', $item['jumlah']);
            }

            // Create pesanan
            $pesanan = Pesanan::create([
                'idcustomer' => $customer->idcustomer,
                'idvendor' => $request->idvendor,
                'total' => $total,
                'status' => 'pending',
            ]);

            // Create detail pesanan
            foreach ($itemDetails as $detail) {
                DetailPesanan::create(array_merge($detail, [
                    'idpesanan' => $pesanan->idpesanan,
                ]));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'idpesanan' => $pesanan->idpesanan,
                'idcustomer' => $customer->idcustomer,
                'total' => $total,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show payment page with Midtrans Snap token.
     */
    public function payment($idpesanan)
    {
        $pesanan = Pesanan::with(['detailPesanan.menu', 'customer', 'vendor'])->findOrFail($idpesanan);

        if ($pesanan->status === 'lunas') {
            return redirect('/success/' . $idpesanan);
        }

        // Configure Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        // Build item details for Midtrans
        $itemDetails = [];
        foreach ($pesanan->detailPesanan as $detail) {
            $itemDetails[] = [
                'id' => $detail->idmenu,
                'price' => (int) $detail->harga,
                'quantity' => $detail->jumlah,
                'name' => substr($detail->menu->nama_menu, 0, 50),
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => 'KANTIN-' . $pesanan->idpesanan . '-' . time(),
                'gross_amount' => (int) $pesanan->total,
            ],
            'customer_details' => [
                'first_name' => $pesanan->customer->nama,
                'email' => 'guest@kantinorder.com',
            ],
            'item_details' => $itemDetails,
            'enabled_payments' => ['bank_transfer', 'gopay', 'shopeepay'],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
        } catch (\Exception $e) {
            $snapToken = null;
        }

        $clientKey = config('midtrans.client_key');
        $isProduction = config('midtrans.is_production');

        return view('customer.payment', compact('pesanan', 'snapToken', 'clientKey', 'isProduction'));
    }

    /**
     * Show success page after payment.
     */
    public function success($idpesanan)
    {
        $pesanan = Pesanan::with(['detailPesanan.menu', 'customer', 'vendor', 'payment'])->findOrFail($idpesanan);

        $qrCode = new \Endroid\QrCode\QrCode(data: (string)$pesanan->idpesanan);
        $writer = new \Endroid\QrCode\Writer\PngWriter();
        $result = $writer->write($qrCode);
        $qrCodeDataUri = $result->getDataUri();

        return view('customer.success', compact('pesanan', 'qrCodeDataUri'));
    }
}
