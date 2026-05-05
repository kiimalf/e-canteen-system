<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'idmenu';
    public $timestamps = false;

    protected $fillable = ['idvendor', 'nama_menu', 'harga', 'stok'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'idvendor', 'idvendor');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'idmenu', 'idmenu');
    }
}
