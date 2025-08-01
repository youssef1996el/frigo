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
        Schema::create('tmp_ligne_marchandise_sortie', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->unsigned();
            $table->string('Etranger')->nullable();
            $table->foreignId('idproduct')->references('id')->on('list_origins')->onDelete('cascade');
            $table->foreignId('iduser')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('idclient')->references('id')->on('clients')->onDelete('cascade');
            $table->foreignId('idcompany')->references('id')->on('companys')->onDelete('cascade');
            $table->foreignId('idlivreur')->references('id')->on('livreurs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tmp_ligne_marchandise_sortie');
    }
};
