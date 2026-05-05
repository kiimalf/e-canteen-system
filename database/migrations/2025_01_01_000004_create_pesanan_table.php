<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('idpesanan');
            $table->string('idcustomer', 100);
            $table->unsignedInteger('idvendor');
            $table->integer('total');
            $table->enum('status', ['pending', 'lunas'])->default('pending');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('idcustomer')->references('idcustomer')->on('customer')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('idvendor')->references('idvendor')->on('vendor')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
