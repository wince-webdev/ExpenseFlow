@extends('layouts.app')
@section('title', 'Dépenses')
@section('page-title', '💸 Gestion des Dépenses')

@section('content')

{{-- BARRE D'ACTIONS : bouton créer + filtres --}}
<div class="bg-white rounded-xl shadow p-4 mb-6">
    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">

        {{-- Bouton Nouvelle Dépense --}}
        <a href="{{ route('expenses.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg transition flex items-center gap-2">
            ＋ Nouvelle dépense
        </a>

        {{-- FORMULAIRE DE FILTRES --}}
        {{-- method="GET" car je filtre sans modifier les données --}}
        {{-- action="" = envoie vers la même URL /expenses --}}
        <form method="GET" action="{{ route('expenses.index') }}"
              class="flex flex-wrap gap-3 items-center">

            {{-- Filtre recherche texte --}}
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Rechercher..."
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

            {{-- Filtre par catégorie --}}
            <select name="category_id"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Toutes catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            {{-- Filtre par statut --}}
            <select name="status"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous statuts</option>
                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>En attente</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approuvé</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeté</option>
            </select>

            {{-- Date début --}}
            <input type="date" name="date_debut"
                   value="{{ request('date_debut') }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

            {{-- Date fin --}}
            <input type="date" name="date_fin"
                   value="{{ request('date_fin') }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

            <button type="submit"
                    class="bg-gray-700 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm transition">
                🔍 Filtrer
            </button>

            {{-- Bouton reset filtres --}}
            {{-- <a href="{{ route('expenses.index') }}"
               class="text-sm text-gray-500 hover:text-red-500 underline">
                Réinitialiser
            </a> --}}

             <a href="{{ route('expenses.index') }}"
                class="inline-block bg-red-600 hover:bg-red-800 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200"
                style="color: white !important;">
                    🔄 Réinitialiser
            </a>
        </form>
    </div>
</div>

{{-- TABLEAU DES DÉPENSES --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 text-gray-600 font-semibold">Titre</th>
                <th class="text-left px-6 py-3 text-gray-600 font-semibold">Catégorie</th>
                <th class="text-left px-6 py-3 text-gray-600 font-semibold">Date</th>
                <th class="text-right px-6 py-3 text-gray-600 font-semibold">Montant</th>
                <th class="text-center px-6 py-3 text-gray-600 font-semibold">Statut</th>
                <th class="text-center px-6 py-3 text-gray-600 font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($expenses as $expense)
            <tr class="hover:bg-gray-50 transition">

                {{-- Titre + soumis par --}}
                <td class="px-6 py-4">
                    <p class="font-medium text-gray-800">{{ $expense->title }}</p>
                    <p class="text-xs text-gray-400">par {{ $expense->user->name }}</p>
                </td>

                {{-- Catégorie avec couleur --}}
                <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1">
                        {{-- Point coloré avec la couleur de la catégorie --}}
                        <span class="w-2 h-2 rounded-full"
                              style="background-color: {{ $expense->category->color }}"></span>
                        {{ $expense->category->name }}
                    </span>
                </td>

                {{-- Date formatée --}}
                <td class="px-6 py-4 text-gray-500">
                    {{ $expense->expense_date->format('d/m/Y') }}
                </td>

                {{-- Montant --}}
                <td class="px-6 py-4 text-right font-bold text-red-600">
                    {{ number_format($expense->amount, 0, ',', ' ') }} FCFA
                </td>

                {{-- Badge statut --}}
                <td class="px-6 py-4 text-center">
                    @if($expense->status === 'approved')
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            ✅ Approuvé
                        </span>
                    @elseif($expense->status === 'pending')
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                            ⏳ En attente
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                            ❌ Rejeté
                        </span>
                    @endif
                </td>

                {{-- Boutons actions --}}
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">

                        {{-- Voir --}}
                        <a href="{{ route('expenses.show', $expense) }}"
                           class="text-blue-600 hover:text-blue-800 font-medium text-xs px-2 py-1 rounded hover:bg-blue-50 transition">
                            👁 Voir
                        </a>

                        {{-- Modifier --}}
                        <a href="{{ route('expenses.edit', $expense) }}"
                           class="text-yellow-600 hover:text-yellow-800 font-medium text-xs px-2 py-1 rounded hover:bg-yellow-50 transition">
                            ✏️ Modifier
                        </a>

                        {{-- Approuver (si pending) --}}
                        @if($expense->status === 'pending')
                            @can('approve expenses')
                            <form method="POST" action="{{ route('expenses.approve', $expense) }}">
                                @csrf
                                @method('PATCH')
                                {{-- @method('PATCH') = champ caché _method=PATCH --}}
                                {{-- HTML ne supporte que GET/POST, Laravel simule PATCH --}}
                                <button type="submit"
                                        class="text-green-600 hover:text-green-800 font-medium text-xs px-2 py-1 rounded hover:bg-green-50 transition">
                                    ✔ Approuver
                                </button>
                            </form>
                            @endcan
                        @endif

                        {{-- Supprimer --}}
                        @can('delete expenses')
                        <form method="POST" action="{{ route('expenses.destroy', $expense) }}"
                              onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 hover:text-red-800 font-medium text-xs px-2 py-1 rounded hover:bg-red-50 transition">
                                🗑 Supprimer
                            </button>
                        </form>
                        @endcan

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-12 text-gray-400">
                    <p class="text-lg">😕 Aucune dépense trouvée</p>
                    <a href="{{ route('expenses.create') }}" class="text-blue-600 hover:underline text-sm mt-2 block">
                        Créer la première dépense
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PAGINATION --}}
    {{-- $expenses->links() génère automatiquement les boutons de pagination --}}
    {{-- En Symfony tu utilisais knp_paginator --}}
    @if($expenses->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $expenses->links() }}
    </div>
    @endif
</div>

@endsection