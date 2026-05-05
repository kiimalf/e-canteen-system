<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class VendorPesananController extends Controller
{
    /**
     * Display orders with status "lunas" for the logged-in vendor.
     */
    public function index()
    {
        $vendorId = session('vendor_id');

        $pesanans = Pesanan::with(['customer', 'detailPesanan.menu'])
            ->where('idvendor', $vendorId)
            ->where('status', 'lunas')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('vendor.pesanan.index', compact('pesanans'));
    }

    /**
     * Get order detail (API for modal).
     */
    public function show($id)
    {
        $vendorId = session('vendor_id');

        $pesanan = Pesanan::with(['customer', 'detailPesanan.menu', 'payment'])
            ->where('idvendor', $vendorId)
            ->where('idpesanan', $id)
            ->firstOrFail();

        return response()->json($pesanan);
    }

    /**
     * Display the QR scanner page for vendors.
     */
    public function scan()
    {
        return view('vendor.pesanan.scan');
    }
}
