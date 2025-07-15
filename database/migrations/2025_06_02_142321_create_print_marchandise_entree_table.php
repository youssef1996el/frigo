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
        Schema::create('print_marchandise_entree', function (Blueprint $table) {
            $table->id();
            $table->integer('number_bon')->unsigned();
            $table->foreignId('idmarchandise_entree')->nullable()->constrained('marchandis_entree')->onDelete('cascade');
            $table->foreignId('idcompany')->references('id')->on('companys')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_marchandise_entree');
    }
};
