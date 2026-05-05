<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'idcustomer';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['idcustomer', 'nama'];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'idcustomer', 'idcustomer');
    }
}
