<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    // INDEX — Liste toutes les dépenses
    public function index(Request $request)
    {
        // On commence une "query" (requête) qu'on va filtrer
        $query = Expense::with(['category', 'user']);

        // FILTRE par statut (si soumis dans l'URL : /expenses?status=pending)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // FILTRE par catégorie
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // FILTRE par date de début
        if ($request->filled('date_debut')) {
            $query->where('expense_date', '>=', $request->date_debut);
        }

        // FILTRE par date de fin
        if ($request->filled('date_fin')) {
            $query->where('expense_date', '<=', $request->date_fin);
        }

        // RECHERCHE par titre
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // paginate(10) = 10 résultats par page avec pagination automatique
        $expenses   = $query->latest()->paginate(10);
        $categories = Category::where('type', 'expense')->get();

        return view('expenses.index', compact('expenses', 'categories'));
    }


    // CREATE — Affiche le formulaire de création
    public function create()
    {
        // On passe les catégories de type "expense" à la vue
        $categories = Category::where('type', 'expense')->get();
        return view('expenses.create', compact('categories'));
    }

    // STORE — Enregistre la nouvelle dépense en base
    public function store(Request $request)
    {
        // Ici Laravel valide directement dans le controller
        // Si validation échoue → retour automatique au formulaire avec erreurs
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'category_id'  => 'required|exists:categories,id', // vérifie que l'ID existe en base
            'status'       => 'required|in:pending,approved,rejected',
            'notes'        => 'nullable|string',
            'attachment'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // max 2MB
        ]);

        // Gérer l'upload du fichier justificatif
        if ($request->hasFile('attachment')) {
            // Storage::disk('public') = dossier storage/app/public
            // store('expenses') = sous-dossier "expenses"
            // Retourne le chemin : "expenses/nomfichier.pdf"
            $validated['attachment'] = $request->file('attachment')
                                               ->store('expenses', 'public');
        }

        // Ajouter l'ID de l'utilisateur connecté
        $validated['user_id'] = Auth::id();

        // Créer la dépense en base
        Expense::create($validated);

        // redirect()->route('expenses.index') = redirection vers la liste
        // ->with('success', '...') = message flash (comme addFlash en Symfony)
        return redirect()->route('expenses.index')
                         ->with('success', 'Dépense créée avec succès !');
    }


    // SHOW — Affiche le détail d'une dépense
    public function show(Expense $expense)
    {
        // Route Model Binding : Laravel récupère automatiquement
        // l'Expense par son ID depuis l'URL
        $expense->load(['category', 'user']); // charge les relations
        return view('expenses.show', compact('expense'));
    }

    // EDIT — Affiche le formulaire de modification
    public function edit(Expense $expense)
    {
        $categories = Category::where('type', 'expense')->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }


    // UPDATE — Met à jour en base
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'category_id'  => 'required|exists:categories,id',
            'status'       => 'required|in:pending,approved,rejected',
            'notes'        => 'nullable|string',
            'attachment'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Si nouveau fichier uploadé
        if ($request->hasFile('attachment')) {
            // Supprimer l'ancien fichier s'il existe
            if ($expense->attachment) {
                Storage::disk('public')->delete($expense->attachment);
            }
            $validated['attachment'] = $request->file('attachment')
                                               ->store('expenses', 'public');
        }

        // update() = UPDATE expenses SET ... WHERE id = ?
        $expense->update($validated);

        return redirect()->route('expenses.index')
                         ->with('success', 'Dépense mise à jour avec succès !');
    }

    // DESTROY — Supprime une dépense
    public function destroy(Expense $expense)
    {
        // Supprimer le fichier joint si existant
        if ($expense->attachment) {
            Storage::disk('public')->delete($expense->attachment);
        }

        $expense->delete(); // DELETE FROM expenses WHERE id = ?

        return redirect()->route('expenses.index')
                         ->with('success', 'Dépense supprimée avec succès !');
    }


    // APPROVE — Approuver une dépense
    // PATCH /expenses/{expense}/approve
    public function approve(Expense $expense)
    {
        $expense->update(['status' => 'approved']);
        return back()->with('success', 'Dépense approuvée !');
    }

    // REJECT — Rejeter une dépense
    // PATCH /expenses/{expense}/reject
    public function reject(Expense $expense)
    {
        $expense->update(['status' => 'rejected']);
        return back()->with('success', 'Dépense rejetée !');
    }
}