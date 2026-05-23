<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // GET /api/categories
    // L'app mobile appelle ça pour remplir les listes déroulantes
    public function index(Request $request)
    {
        // Filtre par type si demandé
        // GET /api/categories?type=expense
        // GET /api/categories?type=revenue
        $query = Category::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // withCount = ajoute expenses_count et revenues_count
        // Utile pour l'app admin qui veut savoir combien de fois une catégorie est utilisée
        $categories = $query->withCount(['expenses', 'revenues'])
                            ->orderBy('name')
                            ->get();

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }

    // POST /api/categories
    // Créer une catégorie (admin seulement)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'type'        => 'required|in:expense,revenue',
            'color'       => 'required|string',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Catégorie créée',
            'data'    => $category,
        ], 201);
    }

    // GET /api/categories/{id}
    public function show(Category $category)
    {
        $category->loadCount(['expenses', 'revenues']);

        return response()->json([
            'success' => true,
            'data'    => $category,
        ]);
    }

    // PUT /api/categories/{id}
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255|unique:categories,name,' . $category->id,
            'type'        => 'sometimes|in:expense,revenue',
            'color'       => 'sometimes|string',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Catégorie mise à jour',
            'data'    => $category,
        ]);
    }

    // DELETE /api/categories/{id}
    public function destroy(Category $category)
    {
        // Vérification avant suppression
        if ($category->expenses()->count() > 0 || $category->revenues()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer : catégorie utilisée dans des dépenses ou revenues.',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catégorie supprimée',
        ]);
    }
}