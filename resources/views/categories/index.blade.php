@extends('layouts.app')
@section('title', 'Catégories')
@section('page-title', '🏷️ Gestion des Catégories')

@section('content')

<div class="flex justify-between items-center mb-6">
    <p class="text-gray-500 text-sm">{{ $categories->count() }} catégorie(s) au total</p>
    <a href="{{ route('categories.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg transition">
        ＋ Nouvelle catégorie
    </a>
</div>

{{-- Deux colonnes : Dépenses | Recettes --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Catégories DÉPENSES --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 bg-red-50 border-b border-red-200">
            <h2 class="font-bold text-red-700">💸 Catégories Dépenses</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-600">Catégorie</th>
                    <th class="text-center px-6 py-3 text-gray-600">Utilisées</th>
                    <th class="text-center px-6 py-3 text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories->where('type', 'expense') as $cat)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            {{-- Carré de couleur --}}
                            <span class="w-4 h-4 rounded"
                                  style="background-color: {{ $cat->color }}"></span>
                            <div>
                                <p class="font-medium text-gray-800">{{ $cat->name }}</p>
                                @if($cat->description)
                                    <p class="text-xs text-gray-400">{{ $cat->description }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                            {{ $cat->expenses_count }} dépense(s)
                        </span>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('categories.edit', $cat) }}"
                               class="text-yellow-600 hover:text-yellow-800 text-xs px-2 py-1 rounded hover:bg-yellow-50">
                                ✏️ Modifier
                            </a>
                            <form method="POST" action="{{ route('categories.destroy', $cat) }}"
                                  onsubmit="return confirm('Supprimer cette catégorie ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-800 text-xs px-2 py-1 rounded hover:bg-red-50">
                                    🗑
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-6 text-gray-400">Aucune catégorie</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Catégories RECETTES --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 bg-green-50 border-b border-green-200">
            <h2 class="font-bold text-green-700">💰 Catégories Recettes</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-600">Catégorie</th>
                    <th class="text-center px-6 py-3 text-gray-600">Utilisées</th>
                    <th class="text-center px-6 py-3 text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories->where('type', 'revenue') as $cat)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            <span class="w-4 h-4 rounded"
                                  style="background-color: {{ $cat->color }}"></span>
                            <div>
                                <p class="font-medium text-gray-800">{{ $cat->name }}</p>
                                @if($cat->description)
                                    <p class="text-xs text-gray-400">{{ $cat->description }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                            {{ $cat->revenues_count }} recette(s)
                        </span>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('categories.edit', $cat) }}"
                               class="text-yellow-600 hover:text-yellow-800 text-xs px-2 py-1 rounded hover:bg-yellow-50">
                                ✏️ Modifier
                            </a>
                            <form method="POST" action="{{ route('categories.destroy', $cat) }}"
                                  onsubmit="return confirm('Supprimer cette catégorie ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-800 text-xs px-2 py-1 rounded hover:bg-red-50">
                                    🗑
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-6 text-gray-400">Aucune catégorie</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection