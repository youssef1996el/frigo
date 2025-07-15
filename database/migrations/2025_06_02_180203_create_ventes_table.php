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
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->integer('number_box')->nullable();
            $table->integer('achteur')->nullable();
            $table->integer('vendeur')->nullable();
            $table->foreignId('idlivreur')->references('id')->on('livreurs')->onDelete('cascade');
            $table->foreignId('idcompany')->references('id')->on('companys')->onDelete('cascade');
            $table->foreignId('iduser')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
