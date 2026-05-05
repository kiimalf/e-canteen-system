<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';
    public $timestamps = false;

    protected $fillable = ['idcustomer', 'idvendor', 'total', 'status'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'idcustomer', 'idcustomer');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'idvendor', 'idvendor');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'idpesanan', 'idpesanan');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'idpesanan', 'idpesanan');
    }
}
