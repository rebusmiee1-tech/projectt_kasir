<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_pembelian', function (Blueprint $table) {
            $table->increments('id_pembelian');

            $table->unsignedInteger('id_sekolah');
            $table->unsignedInteger('id_supplier');
            $table->unsignedInteger('id_user');

            $table->string('nomor_faktur', 50);
            $table->date('tanggal_faktur');
            $table->decimal('total_bayar', 15, 2)->default(0);

            $table->enum('status_pembelian', ['draft', 'selesai'])
                ->default('draft');

            $table->enum('jenis_transaksi', ['tunai', 'kredit'])
                ->default('tunai');

            $table->string('cara_bayar', 50)->nullable();
            $table->text('catatan')->nullable();

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

            $table->foreign('id_supplier')
                ->references('id_supplier')
                ->on('tb_supplier')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('id_user')
                ->references('id_user')
                ->on('tb_user')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pembelian');
    }
};