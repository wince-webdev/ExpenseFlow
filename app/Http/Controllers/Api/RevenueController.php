<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Revenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevenueController extends Controller
{
    // GET /api/revenues
    // Liste toutes les revenues avec pagination et filtres
    public function index(Request $request)
    {
        // On construit la requête progressivement
        // on ajoute les filtres conditionnellement
        $query = Revenue::with(['category', 'user']);

        // Filtre par catégorie si paramètre présent dans l'URL
        // Ex: GET /api/revenues?category_id=2
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filtre par date de début
        // Ex: GET /api/revenues?date_debut=2026-05-01
        if ($request->filled('date_debut')) {
            $query->where('revenue_date', '>=', $request->date_debut);
        }

        // Filtre par date de fin
        if ($request->filled('date_fin')) {
            $query->where('revenue_date', '<=', $request->date_fin);
        }

        // Recherche par titre
        // LIKE '%mot%' = contient ce mot n'importe où
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // paginate(15) = 15 revenues par page
        // latest() = ORDER BY created_at DESC (plus récentes en premier)
        $revenues = $query->latest()->paginate(15);

        // Retourner JSON structuré
        return response()->json([
            'success' => true,
            'data'    => $revenues->items(), // les données de la page courante
            'meta'    => [
                'total'        => $revenues->total(),        // total en base
                'per_page'     => $revenues->perPage(),      // par page
                'current_page' => $revenues->currentPage(),  // page actuelle
                'last_page'    => $revenues->lastPage(),     // dernière page
            ],
        ]);
    }

    // POST /api/revenues
    // Créer une nouvelle revenue
    // Body JSON attendu : { title, amount, revenue_date, category_id }
    public function store(Request $request)
    {
        // Validation des données reçues
        // Si validation échoue → Laravel retourne JSON 422 automatiquement
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'revenue_date' => 'required|date',
            'category_id'  => 'required|exists:categories,id',
            // exists:categories,id = vérifie que cet ID existe dans la table categories
            'notes'        => 'nullable|string',
        ]);

        // Ajouter l'ID de l'utilisateur connecté automatiquement
        // Auth::id() = ID de l'user identifié par le token Sanctum
        $validated['user_id'] = Auth::id();

        // Créer en base avec les données validées
        $revenue = Revenue::create($validated);

        // Charger les relations pour les retourner dans la réponse
        $revenue->load(['category', 'user']);

        // 201 = HTTP Created → bonne pratique REST pour une création
        return response()->json([
            'success' => true,
            'message' => 'Revenue créée avec succès',
            'data'    => $revenue,
        ], 201);
    }

    // GET /api/revenues/{id}
    // Afficher le détail d'une revenue
    // Laravel trouve automatiquement la revenue par l'ID dans l'URL
    // C'est le Route Model Binding
    public function show(Revenue $revenue)
    {
        // $revenue est automatiquement récupéré par Laravel
        // Si l'ID n'existe pas → Laravel retourne 404 automatiquement
        $revenue->load(['category', 'user']);

        return response()->json([
            'success' => true,
            'data'    => $revenue,
        ]);
    }

    // PUT /api/revenues/{id}
    // Mettre à jour une revenue
    public function update(Request $request, Revenue $revenue)
    {
        // 'sometimes' = valider ce champ SEULEMENT s'il est présent
        // Pourquoi ? En API on peut envoyer seulement les champs à modifier
        // Ex: { "amount": 75000 } → modifie seulement le montant
        $validated = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'amount'       => 'sometimes|numeric|min:0',
            'revenue_date' => 'sometimes|date',
            'category_id'  => 'sometimes|exists:categories,id',
            'notes'        => 'nullable|string',
        ]);

        // update() = UPDATE revenues SET ... WHERE id = ?
        $revenue->update($validated);

        // Recharger les relations après update
        $revenue->load(['category', 'user']);

        return response()->json([
            'success' => true,
            'message' => 'Revenue mise à jour',
            'data'    => $revenue,
        ]);
    }

    // DELETE /api/revenues/{id}
    // Supprimer une revenue
    public function destroy(Revenue $revenue)
    {
        $revenue->delete();

        // 200 avec message de confirmation
        return response()->json([
            'success' => true,
            'message' => 'Revenue supprimée avec succès',
        ]);
    }
}