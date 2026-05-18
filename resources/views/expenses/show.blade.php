@extends('layouts.app')
@section('title', 'Détail dépense')
@section('page-title', '👁 Détail de la Dépense')

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Bouton retour --}}
    <div class="mb-4">
        <a href="{{ route('expenses.index') }}"
           class="text-sm text-gray-500 hover:text-blue-600 inline-flex items-center gap-1">
            ← Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        {{-- En-tête colorée selon le statut --}}
        <div class="p-6 border-b
            {{ $expense->status === 'approved' ? 'bg-green-50 border-green-200' :
               ($expense->status === 'rejected' ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200') }}">

            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $expense->title }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Soumis par <strong>{{ $expense->user->name }}</strong>
                        le {{ $expense->created_at->format('d/m/Y à H:i') }}
                    </p>
                </div>

                {{-- Badge statut --}}
                @if($expense->status === 'approved')
                    <span class="px-4 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                        ✅ Approuvé
                    </span>
                @elseif($expense->status === 'pending')
                    <span class="px-4 py-1.5 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700">
                        ⏳ En attente
                    </span>
                @else
                    <span class="px-4 py-1.5 rounded-full text-sm font-semibold bg-red-100 text-red-700">
                        ❌ Rejeté
                    </span>
                @endif
            </div>
        </div>

        {{-- Détails --}}
        <div class="p-6 grid grid-cols-2 gap-6">

            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Montant</p>
                <p class="text-2xl font-bold text-red-600">
                    {{ number_format($expense->amount, 0, ',', ' ') }} FCFA
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Date</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $expense->expense_date->format('d/m/Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Catégorie</p>
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium"
                      style="background-color: {{ $expense->category->color }}22; color: {{ $expense->category->color }}">
                    ● {{ $expense->category->name }}
                </span>
            </div>

            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Soumis par</p>
                <p class="text-sm font-medium text-gray-700">{{ $expense->user->name }}</p>
            </div>

            @if($expense->notes)
            <div class="col-span-2">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Notes</p>
                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">
                    {{ $expense->notes }}
                </p>
            </div>
            @endif

            @if($expense->attachment)
            <div class="col-span-2">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Justificatif</p>
                <a href="{{ Storage::url($expense->attachment) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 hover:bg-blue-100
                          text-blue-600 rounded-lg text-sm font-medium transition">
                    📎 Télécharger le justificatif
                </a>
            </div>
            @endif

        </div>

        {{-- Actions --}}
        <div class="px-6 pb-6 flex gap-3">
            <a href="{{ route('expenses.edit', $expense) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg transition">
                Modifier
            </a>

            @if($expense->status === 'pending')
                @can('approve expenses')
                <form method="POST" action="{{ route('expenses.approve', $expense) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded-lg transition">
                        ✔ Approuver
                    </button>
                </form>
                <form method="POST" action="{{ route('expenses.reject', $expense) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2 rounded-lg transition">
                        ✖ Rejeter
                    </button>
                </form>
                @endcan
            @endif
        </div>
    </div>
</div>

@endsection