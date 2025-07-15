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
        Schema::create('display_with_company', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idcompany')->references('id')->on('companys')->onDelete('cascade');
            $table->integer('idpermission');
            $table->string('role');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('display_with_company');
    }
};
