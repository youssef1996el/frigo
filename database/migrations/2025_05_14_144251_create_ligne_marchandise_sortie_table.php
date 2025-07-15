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
        Schema::create('ligne_marchandise_sortie', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->unsigned();
            $table->string('Etranger')->nullable();
            $table->foreignId('id_marchandise_sortie')->references('id')->on('marchandise_sortie')->onDelete('cascade');
            $table->foreignId('idproduct')->references('id')->on('list_origins')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_marchandise_sortie');
    }
};
