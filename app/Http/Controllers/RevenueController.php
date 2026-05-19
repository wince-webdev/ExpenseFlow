<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevenueController extends Controller
{
    // INDEX — Liste toutes les revenues
    public function index(Request $request)
    {
        $query = Revenue::with(['category', 'user']);

        // Filtre recherche texte
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filtre par catégorie
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filtre par date début
        if ($request->filled('date_debut')) {
            $query->where('revenue_date', '>=', $request->date_debut);
        }

        // Filtre par date fin
        if ($request->filled('date_fin')) {
            $query->where('revenue_date', '<=', $request->date_fin);
        }

        $revenues   = $query->latest()->paginate(10);
        $categories = Category::where('type', 'revenue')->get();

        return view('revenues.index', compact('revenues', 'categories'));
    }


    // CREATE — Formulaire de création
    public function create()
    {
        $categories = Category::where('type', 'revenue')->get();
        return view('revenues.create', compact('categories'));
    }


    // STORE — Enregistrer en base
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'revenue_date' => 'required|date',
            'category_id'  => 'required|exists:categories,id',
            'notes'        => 'nullable|string',
        ]);

        // Ajouter l'utilisateur connecté
        $validated['user_id'] = Auth::id();

        Revenue::create($validated);

        return redirect()->route('revenues.index')
                         ->with('success', 'Revenue créée avec succès !');
    }


    // SHOW — Détail d'une revenue
    public function show(Revenue $revenue)
    {
        // Route Model Binding : Laravel récupère automatiquement la Revenue dont l'ID correspond à l'URL
        $revenue->load(['category', 'user']);
        return view('revenues.show', compact('revenue'));
    }

    // EDIT — Formulaire modification    
    public function edit(Revenue $revenue)
    {
        $categories = Category::where('type', 'revenue')->get();
        return view('revenues.edit', compact('revenue', 'categories'));
    }


    // UPDATE — Mettre à jour en base
    public function update(Request $request, Revenue $revenue)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'revenue_date' => 'required|date',
            'category_id'  => 'required|exists:categories,id',
            'notes'        => 'nullable|string',
        ]);

        $revenue->update($validated);

        return redirect()->route('revenues.index')
                         ->with('success', 'Revenue mise à jour avec succès !');
    }


    // DESTROY — Supprimer
    public function destroy(Revenue $revenue)
    {
        $revenue->delete();

        return redirect()->route('revenues.index')
                         ->with('success', 'Revenue supprimée avec succès !');
    }
}