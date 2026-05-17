<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                              // Nom : Loyer, Carburant, Vente...
            $table->enum('type', ['expense', 'revenue']);        // Pour dépense ou recette
            $table->string('color')->default('#3B82F6');         // Couleur pour les graphiques
            $table->text('description')->nullable();             // Description optionnelle
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};