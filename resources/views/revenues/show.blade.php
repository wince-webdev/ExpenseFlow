@extends('layouts.app')
@section('title', 'Détail recette')
@section('page-title', '👁 Détail de la Recette')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-4">
        <a href="{{ route('revenues.index') }}"
           class="text-sm text-gray-500 hover:text-blue-600 inline-flex items-center gap-1">
            ← Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="p-6 border-b bg-green-50 border-green-200">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $revenue->title }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Enregistré par <strong>{{ $revenue->user->name }}</strong>
                        le {{ $revenue->created_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
                <span class="px-4 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                    💰 Recette
                </span>
            </div>
        </div>

        <div class="p-6 grid grid-cols-2 gap-6">

            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Montant</p>
                <p class="text-2xl font-bold text-green-600">
                    {{ number_format($revenue->amount, 0, ',', ' ') }} FCFA
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Date</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $revenue->revenue_date->format('d/m/Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Catégorie</p>
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium"
                      style="background-color: {{ $revenue->category->color }}22; color: {{ $revenue->category->color }}">
                    ● {{ $revenue->category->name }}
                </span>
            </div>

            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Enregistré par</p>
                <p class="text-sm font-medium text-gray-700">{{ $revenue->user->name }}</p>
            </div>

            @if($revenue->notes)
            <div class="col-span-2">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Notes</p>
                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $revenue->notes }}</p>
            </div>
            @endif

        </div>

        <div class="px-6 pb-6 flex gap-3">
            <a href="{{ route('revenues.edit', $revenue) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-5 py-2 rounded-lg transition">
                ✏️ Modifier
            </a>
            <form method="POST" action="{{ route('revenues.destroy', $revenue) }}"
                  onsubmit="return confirm('Confirmer la suppression ?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2 rounded-lg transition">
                    🗑 Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

@endsection