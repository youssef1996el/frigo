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
        Schema::create('ligne_marchandis', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->unsigned();
            $table->string('Etranger')->nullable();
            $table->foreignId('id_marchandis_entree')->references('id')->on('marchandis_entree')->onDelete('cascade');
            $table->foreignId('idproduct')->references('id')->on('list_origins')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_marchandis');
    }
};
