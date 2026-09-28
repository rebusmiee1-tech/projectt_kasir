<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_pelanggan', function (Blueprint $table) {
    $table->increments('id_pelanggan');
    $table->unsignedInteger('id_kelompok_pelanggan');

    $table->string('nama_pelanggan', 100);
    $table->string('telepon', 20)->nullable();
    $table->text('alamat')->nullable();

    $table->timestamp('created_at')->useCurrent();
    $table->unsignedBigInteger('created_by')->nullable();
    $table->timestamp('updated_at')->nullable();
    $table->unsignedBigInteger('updated_by')->nullable();
    $table->timestamp('deleted_at')->nullable();
    $table->unsignedBigInteger('deleted_by')->nullable();

    $table->foreign('id_kelompok_pelanggan')
        ->references('id_kelompok_pelanggan')
        ->on('tb_kelompok_pelanggan')
        ->onDelete('cascade')
        ->onUpdate('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pelanggan');
    }
};
