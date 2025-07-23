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
        Schema::create('marchandis_entree', function (Blueprint $table) {
            $table->id();
            $table->integer('number_box')->unsigned();
            $table->integer('cumul')->unsigned();
            $table->string('etranger')->nullable();
            $table->string('type')->default('normale');
            $table->boolean('clotuer')->default(false);

            $table->foreignId('iduser')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('idclient')->nullable();
            $table->foreign('idclient')->references('id')->on('clients')->onDelete('set null');

            //$table->foreignId('idclient')->references('id')->on('clients')->onDelete('cascade');

            $table->foreignId('idlivreur')->references('id')->on('livreurs')->onDelete('cascade');
            $table->foreignId('idcompany')->references('id')->on('companys')->onDelete('cascade');
            $table->integer('idvente')->nullable();
            $table->integer('idclient_tmp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marchandise_entree');
    }
};
