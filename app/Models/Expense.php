<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'amount',
        'expense_date',
        'status',
        'attachment',
        'notes',
    ];

    // Convertir automatiquement expense_date en objet Carbon (date)
    // Carbon = DateTime de Laravel (comme \DateTime en Symfony)
    protected $casts = [
        'expense_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    // RELATION : Une dépense appartient à un User
    // En Symfony : ManyToOne vers User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELATION : Une dépense appartient à une Catégorie
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}