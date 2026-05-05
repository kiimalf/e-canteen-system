<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->increments('idmenu');
            $table->unsignedInteger('idvendor');
            $table->string('nama_menu', 255);
            $table->integer('harga');
            $table->integer('stok')->default(0);

            $table->foreign('idvendor')->references('idvendor')->on('vendor')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
