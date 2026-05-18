<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Récupérer toutes les catégories
        // withCount() = ajoute un attribut expenses_count et revenues_count
        // sans faire de requête séparée
        $categories = Category::withCount(['expenses', 'revenues'])
                               ->orderBy('type')
                               ->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            // unique:categories,name = vérifie que le nom n'existe pas déjà
            'type'        => 'required|in:expense,revenue',
            'color'       => 'required|string',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
                         ->with('success', 'Catégorie créée avec succès !');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name,' . $category->id,
            // unique:categories,name,{id} = unique SAUF pour cet enregistrement lui-même
            'type'        => 'required|in:expense,revenue',
            'color'       => 'required|string',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
                         ->with('success', 'Catégorie mise à jour !');
    }

    public function destroy(Category $category)
    {
        // Vérifier si la catégorie est utilisée avant de supprimer
        if ($category->expenses()->count() > 0 || $category->revenues()->count() > 0) {
            return redirect()->route('categories.index')
                             ->with('error', 'Impossible de supprimer : cette catégorie est utilisée.');
        }

        $category->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'Catégorie supprimée !');
    }

    // show() non nécessaire pour les catégories
    public function show(Category $category) {}
}