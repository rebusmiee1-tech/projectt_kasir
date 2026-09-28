<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_detail_penjualan', function (Blueprint $table) {
            $table->increments('id_detail_penjualan');

            $table->unsignedInteger('id_penjualan');
            $table->unsignedInteger('id_barang');

            $table->integer('jumlah_barang')->default(0);
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);

            $table->enum('diskon_tipe', ['persen', 'nominal'])
                ->nullable();

            $table->decimal('diskon_nilai', 15, 2)->default(0);
            $table->decimal('diskon_nominal', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);

            $table->foreign('id_penjualan')
                ->references('id_penjualan')
                ->on('tb_penjualan')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('tb_barang')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_detail_penjualan');
    }
};