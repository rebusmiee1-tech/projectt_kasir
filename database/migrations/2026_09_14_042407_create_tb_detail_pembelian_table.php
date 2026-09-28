<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_detail_pembelian', function (Blueprint $table) {
            $table->increments('id_detail_pembelian');

            $table->unsignedInteger('id_pembelian');
            $table->unsignedInteger('id_barang');

            $table->string('satuan', 30)->nullable();
            $table->integer('jumlah')->default(0);
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);

            $table->foreign('id_pembelian')
                ->references('id_pembelian')
                ->on('tb_pembelian')
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
        Schema::dropIfExists('tb_detail_pembelian');
    }
};