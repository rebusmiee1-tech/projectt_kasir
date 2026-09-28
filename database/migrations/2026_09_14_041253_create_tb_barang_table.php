<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_barang', function (Blueprint $table) {
            $table->increments('id_barang');

            $table->unsignedInteger('id_sekolah');
            $table->string('barcode', 50)->nullable();
            $table->string('nama', 150);

            $table->unsignedInteger('id_kategori')->nullable();
            $table->unsignedInteger('id_kelompok_kategori')->nullable();
            $table->unsignedInteger('id_supplier')->nullable();

            $table->string('satuan', 30)->nullable();
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->integer('stok')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamp('created_at')->useCurrent();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('id_sekolah')
                ->references('id_sekolah')
                ->on('tb_sekolah')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_kategori')
                ->references('id_kategori')
                ->on('tb_kategori')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('id_kelompok_kategori')
                ->references('id_kelompok')
                ->on('tb_kelompok_kategori')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('id_supplier')
                ->references('id_supplier')
                ->on('tb_supplier')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_barang');
    }
};