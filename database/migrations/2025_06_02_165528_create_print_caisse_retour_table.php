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
        Schema::create('print_caisse_retour', function (Blueprint $table) {
            $table->id();
            $table->integer('number_bon')->unsigned();
            $table->foreignId('idcaisseretour')->nullable()->constrained('caisse_retour')->onDelete('cascade');
            $table->foreignId('idcompany')->references('id')->on('companys')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_caisse_retour');
    }
};
