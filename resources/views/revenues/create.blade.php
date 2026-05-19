@extends('layouts.app')
@section('title', 'Nouveau revenue')
@section('page-title', '💰 Nouveau Revenue')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow p-8">

        <a href="{{ route('revenues.index') }}"
           class="text-sm text-gray-500 hover:text-blue-600 mb-6 inline-flex items-center gap-1">
            ← Retour à la liste
        </a>

        <h2 class="text-xl font-bold text-gray-800 mb-6 mt-2">Informations du revenue</h2>

        <form method="POST" action="{{ route('revenues.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Titre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           placeholder="Ex: Vente produits informatiques"
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500
                                  {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Montant (FCFA) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">FCFA</span>
                        <input type="number" name="amount" value="{{ old('amount') }}"
                               placeholder="0" min="0" step="1"
                               class="w-full border rounded-lg pl-14 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500
                                      {{ $errors->has('amount') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    </div>
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="revenue_date"
                           value="{{ old('revenue_date', now()->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('revenue_date')
                        <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id"
                            class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500
                                   {{ $errors->has('category_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        <option value="">-- Choisir une catégorie --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="3" placeholder="Remarques..."
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none">{{ old('notes') }}</textarea>
                </div>

            </div>
            <br>

            <hr class="my-6 border-gray-200">
            <br>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-2.5 rounded-lg transition">
                    Enregistrer le revenue
                </button>
                <a href="{{ route('revenues.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-2.5 rounded-lg transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection