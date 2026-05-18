@extends('layouts.app')
@section('title', 'Recettes')
@section('page-title', '💰 Gestion des Recettes')

@section('content')

<div class="bg-white rounded-xl shadow p-4 mb-6">
    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">

        <a href="{{ route('revenues.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded-lg transition flex items-center gap-2">
            ＋ Nouvelle recette
        </a>

        <form method="GET" action="{{ route('revenues.index') }}"
              class="flex flex-wrap gap-3 items-center">

            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Rechercher..."
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">

            <select name="category_id"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Toutes catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">

            <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">

            <button type="submit"
                    class="bg-gray-700 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm transition">
                🔍 Filtrer
            </button>

            {{-- <a href="{{ route('revenues.index') }}" class="text-sm text-gray-500 hover:text-red-500 underline">
                Réinitialiser
            </a> --}}

            <a href="{{ route('revenues.index') }}"
                class="inline-block bg-red-600 hover:bg-red-800 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200"
                style="color: white !important;">
                    🔄 Réinitialiser
            </a>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 text-gray-600 font-semibold">Titre</th>
                <th class="text-left px-6 py-3 text-gray-600 font-semibold">Catégorie</th>
                <th class="text-left px-6 py-3 text-gray-600 font-semibold">Date</th>
                <th class="text-right px-6 py-3 text-gray-600 font-semibold">Montant</th>
                <th class="text-center px-6 py-3 text-gray-600 font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($revenues as $revenue)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <p class="font-medium text-gray-800">{{ $revenue->title }}</p>
                    <p class="text-xs text-gray-400">par {{ $revenue->user->name }}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full"
                              style="background-color: {{ $revenue->category->color }}"></span>
                        {{ $revenue->category->name }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-500">
                    {{ $revenue->revenue_date->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 text-right font-bold text-green-600">
                    {{ number_format($revenue->amount, 0, ',', ' ') }} FCFA
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('revenues.show', $revenue) }}"
                           class="text-blue-600 hover:text-blue-800 text-xs px-2 py-1 rounded hover:bg-blue-50 transition">
                            👁 Voir
                        </a>
                        <a href="{{ route('revenues.edit', $revenue) }}"
                           class="text-yellow-600 hover:text-yellow-800 text-xs px-2 py-1 rounded hover:bg-yellow-50 transition">
                            ✏️ Modifier
                        </a>
                        <form method="POST" action="{{ route('revenues.destroy', $revenue) }}"
                              onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 hover:text-red-800 text-xs px-2 py-1 rounded hover:bg-red-50 transition">
                                🗑 Supprimer
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-12 text-gray-400">
                    <p class="text-lg">😕 Aucune recette trouvée</p>
                    <a href="{{ route('revenues.create') }}" class="text-green-600 hover:underline text-sm mt-2 block">
                        Créer la première recette
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($revenues->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $revenues->links() }}
    </div>
    @endif
</div>

@endsection