<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->increments('iddetail_pesanan');
            $table->unsignedInteger('idpesanan');
            $table->unsignedInteger('idmenu');
            $table->integer('jumlah');
            $table->decimal('harga', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->string('catatan', 255)->nullable();

            $table->foreign('idpesanan')->references('idpesanan')->on('pesanan')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('idmenu')->references('idmenu')->on('menu')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
