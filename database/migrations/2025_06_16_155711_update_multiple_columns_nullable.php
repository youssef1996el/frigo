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
        Schema::table('caissevides', function (Blueprint $table) {
            $table->dropForeign(['idlivreur']);
            $table->unsignedBigInteger('idlivreur')->nullable()->change();
            $table->foreign('idlivreur')->references('id')->on('livreurs')->onDelete('set null');
        });

        Schema::table('caisse_retour', function (Blueprint $table) {
            $table->dropForeign(['idlivreur']);
            $table->unsignedBigInteger('idlivreur')->nullable()->change();
            $table->foreign('idlivreur')->references('id')->on('livreurs')->onDelete('set null');
        });

        Schema::table('marchandis_entree', function (Blueprint $table) {
            $table->dropForeign(['idlivreur']);
            $table->unsignedBigInteger('idlivreur')->nullable()->change();
            $table->foreign('idlivreur')->references('id')->on('livreurs')->onDelete('set null');
        });

        Schema::table('marchandise_sortie', function (Blueprint $table) {
            $table->dropForeign(['idlivreur']);
            $table->unsignedBigInteger('idlivreur')->nullable()->change();
            $table->foreign('idlivreur')->references('id')->on('livreurs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
