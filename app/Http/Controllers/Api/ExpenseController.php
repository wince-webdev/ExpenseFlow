<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    // GET /api/expenses
    public function index(Request $request)
    {
        $query = Expense::with(['category', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $expenses = $query->latest()->paginate(15);

        // En API on retourne toujours du JSON
        // response()->json() = retourne une réponse HTTP avec Content-Type: application/json
        return response()->json([
            'success' => true,
            'data'    => $expenses->items(),
            'meta'    => [
                'total'        => $expenses->total(),
                'per_page'     => $expenses->perPage(),
                'current_page' => $expenses->currentPage(),
                'last_page'    => $expenses->lastPage(),
            ],
        ]);
    }

    // POST /api/expenses
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'category_id'  => 'required|exists:categories,id',
            'status'       => 'sometimes|in:pending,approved,rejected',
            'notes'        => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status']  = $validated['status'] ?? 'pending';

        $expense = Expense::create($validated);
        $expense->load(['category', 'user']);

        // 201 = HTTP Created (bonne pratique REST)
        return response()->json([
            'success' => true,
            'message' => 'Dépense créée avec succès',
            'data'    => $expense,
        ], 201);
    }

    // GET /api/expenses/{id}
    public function show(Expense $expense)
    {
        $expense->load(['category', 'user']);

        return response()->json([
            'success' => true,
            'data'    => $expense,
        ]);
    }

    // PUT /api/expenses/{id}
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'title'        => 'sometimes|string|max:255',
            'amount'       => 'sometimes|numeric|min:0',
            'expense_date' => 'sometimes|date',
            'category_id'  => 'sometimes|exists:categories,id',
            'status'       => 'sometimes|in:pending,approved,rejected',
            'notes'        => 'nullable|string',
        ]);

        $expense->update($validated);
        $expense->load(['category', 'user']);

        return response()->json([
            'success' => true,
            'message' => 'Dépense mise à jour',
            'data'    => $expense,
        ]);
    }

    // DELETE /api/expenses/{id}
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dépense supprimée',
        ]);
    }
}