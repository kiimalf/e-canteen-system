<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class VendorMenuController extends Controller
{
    /**
     * Display vendor's menu list.
     */
    public function index()
    {
        $vendorId = session('vendor_id');
        $menus = Menu::where('idvendor', $vendorId)->get();
        return view('vendor.menu.index', compact('menus'));
    }

    /**
     * Store a new menu item (Axios).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $menu = Menu::create([
            'idvendor' => session('vendor_id'),
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan!',
            'menu' => $menu,
        ]);
    }

    /**
     * Update a menu item (Axios).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $menu = Menu::where('idmenu', $id)
            ->where('idvendor', session('vendor_id'))
            ->firstOrFail();

        $menu->update([
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui!',
            'menu' => $menu,
        ]);
    }

    /**
     * Delete a menu item (Axios).
     */
    public function destroy($id)
    {
        $menu = Menu::where('idmenu', $id)
            ->where('idvendor', session('vendor_id'))
            ->firstOrFail();

        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dihapus!',
        ]);
    }
}
