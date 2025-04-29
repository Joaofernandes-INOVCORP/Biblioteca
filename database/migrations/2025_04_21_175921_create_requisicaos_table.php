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
        Schema::create('requisicaos', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();           
            $table->foreignId('livro_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('data_inicio');
            $table->date('data_prevista_fim');
            $table->date('data_real_fim')->nullable();
            $table->enum('status',['ativa','entregue','cancelada'])->default('ativa');
            $table->string('foto_cidadao')->nullable();  
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisicaos');
    }
};
