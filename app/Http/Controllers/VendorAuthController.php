<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorAuthController extends Controller
{
    /**
     * Show vendor login page.
     */
    public function showLogin()
    {
        $vendors = Vendor::all();
        return view('vendor.login', compact('vendors'));
    }

    /**
     * Process vendor login (simple session-based, no password).
     */
    public function login(Request $request)
    {
        $request->validate([
            'idvendor' => 'required|exists:vendor,idvendor',
        ]);

        $vendor = Vendor::findOrFail($request->idvendor);

        session([
            'vendor_id' => $vendor->idvendor,
            'vendor_nama' => $vendor->nama_vendor,
        ]);

        return redirect()->route('vendor.menu.index')
            ->with('success');
    }

    /**
     * Logout vendor.
     */
    public function logout()
    {
        session()->forget(['vendor_id', 'vendor_nama']);
        return redirect()->route('vendor.login')
            ->with('success');
    }
}
