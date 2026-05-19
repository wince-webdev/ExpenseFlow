<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('category_id')
                  ->constrained()
                  ->onDelete('restrict');
            $table->string('title');                             // Titre de la revenue : Vente de vélo, Salaire...
            $table->decimal('amount', 15, 2);                   // Montant
            $table->date('revenue_date');                        // Date
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revenues');  
    }
};