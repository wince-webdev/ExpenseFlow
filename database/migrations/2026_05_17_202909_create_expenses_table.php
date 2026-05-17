<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');        // Si user supprimé → ses dépenses supprimées
            $table->foreignId('category_id')
                  ->constrained()
                  ->onDelete('restrict');       // Impossible de supprimer une catégorie utilisée
            $table->string('title');                             // Titre de la dépense
            $table->decimal('amount', 15, 2);                   // Montant ex: 25000.00 FCFA
            $table->date('expense_date');                        // Date de la dépense
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending');                          // Statut par défaut = en attente
            $table->string('attachment')->nullable();            // Chemin justificatif PDF/image
            $table->text('notes')->nullable();                   // Notes optionnelles
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};