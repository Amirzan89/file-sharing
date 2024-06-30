<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->uuid('uuid');
            $table->string('order_id');
            $table->integer('price');
            $table->enum('status', ['cancel_user', 'failed', 'pending','success']);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            $table->unsignedBigInteger('id_pricing');
            $table->foreign('id_pricing')->references('id_pricing')->on('pricing')->onDelete('cascade');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};