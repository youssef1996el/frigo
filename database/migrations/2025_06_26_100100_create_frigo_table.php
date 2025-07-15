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
        Schema::create('frigo', function (Blueprint $table) {
            $table->id();
             // Date of the operation
            $table->date('date');

            // Foreign key to 'charges' table
            $table->unsignedBigInteger('charge_id')->nullable();
            $table->foreign('charge_id')->references('id')->on('charges')->onDelete('set null');

            // Dotation amount (money input)
            $table->integer('dotation')->nullable();

            // Montant: amount shown (either dotation or charge value)
            $table->float('montant')->nullable();

            // Optional: store cumulative values (can be calculated)
            $table->integer('cumul_dotation')->nullable();
            $table->integer('cumul_charge')->nullable();
            $table->foreignId('idcomptabilite')->references('id')->on('comptabilite')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frigo');
    }
};
