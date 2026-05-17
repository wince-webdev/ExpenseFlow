<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Champs que l'on autorise à remplir en masse
    // (protection contre les attaques de masse assignment)
    // En Symfony tu n'avais pas ça car les formulaires géraient ça
    protected $fillable = [
        'name',
        'type',
        'color',
        'description',
    ];

    // RELATION : Une catégorie a plusieurs dépenses
    // En Symfony : OneToMany dans l'entité
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // RELATION : Une catégorie a plusieurs recettes
    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }
}