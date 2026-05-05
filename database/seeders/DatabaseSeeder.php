<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\Menu;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create vendors
        $vendor1 = Vendor::create(['nama_vendor' => 'Warung Nasi Bu Ani']);
        $vendor2 = Vendor::create(['nama_vendor' => 'Kedai Mie Pak Joko']);
        $vendor3 = Vendor::create(['nama_vendor' => 'Juice & Snack Corner']);

        // Menu for Warung Nasi Bu Ani
        Menu::insert([
            ['idvendor' => $vendor1->idvendor, 'nama_menu' => 'Nasi Goreng Spesial', 'harga' => 15000, 'stok' => 50],
            ['idvendor' => $vendor1->idvendor, 'nama_menu' => 'Nasi Ayam Geprek', 'harga' => 18000, 'stok' => 30],
            ['idvendor' => $vendor1->idvendor, 'nama_menu' => 'Nasi Rendang', 'harga' => 20000, 'stok' => 25],
            ['idvendor' => $vendor1->idvendor, 'nama_menu' => 'Nasi Telur Dadar', 'harga' => 12000, 'stok' => 40],
            ['idvendor' => $vendor1->idvendor, 'nama_menu' => 'Es Teh Manis', 'harga' => 5000, 'stok' => 100],
        ]);

        // Menu for Kedai Mie Pak Joko
        Menu::insert([
            ['idvendor' => $vendor2->idvendor, 'nama_menu' => 'Mie Ayam Bakso', 'harga' => 15000, 'stok' => 40],
            ['idvendor' => $vendor2->idvendor, 'nama_menu' => 'Mie Goreng Spesial', 'harga' => 13000, 'stok' => 35],
            ['idvendor' => $vendor2->idvendor, 'nama_menu' => 'Bakso Urat Jumbo', 'harga' => 18000, 'stok' => 20],
            ['idvendor' => $vendor2->idvendor, 'nama_menu' => 'Pangsit Goreng', 'harga' => 8000, 'stok' => 50],
            ['idvendor' => $vendor2->idvendor, 'nama_menu' => 'Es Jeruk Segar', 'harga' => 6000, 'stok' => 80],
        ]);

        // Menu for Juice & Snack Corner
        Menu::insert([
            ['idvendor' => $vendor3->idvendor, 'nama_menu' => 'Jus Alpukat', 'harga' => 12000, 'stok' => 30],
            ['idvendor' => $vendor3->idvendor, 'nama_menu' => 'Jus Mangga', 'harga' => 10000, 'stok' => 30],
            ['idvendor' => $vendor3->idvendor, 'nama_menu' => 'Kentang Goreng', 'harga' => 12000, 'stok' => 25],
            ['idvendor' => $vendor3->idvendor, 'nama_menu' => 'Roti Bakar Coklat', 'harga' => 10000, 'stok' => 20],
            ['idvendor' => $vendor3->idvendor, 'nama_menu' => 'Pisang Nugget', 'harga' => 8000, 'stok' => 35],
        ]);
    }
}
