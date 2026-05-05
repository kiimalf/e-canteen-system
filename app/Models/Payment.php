<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'idpayment';
    public $timestamps = false;

    protected $fillable = ['idpesanan', 'metode_bayar', 'transaction_id', 'status'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'idpesanan', 'idpesanan');
    }
}
