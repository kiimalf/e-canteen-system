<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->increments('idpayment');
            $table->unsignedInteger('idpesanan');
            $table->enum('metode_bayar', ['VA', 'QRIS']);
            $table->string('transaction_id', 255)->nullable();
            $table->enum('status', ['pending', 'settlement', 'expire', 'cancel'])->default('pending');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('idpesanan')->references('idpesanan')->on('pesanan')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
