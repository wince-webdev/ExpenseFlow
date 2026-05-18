@extends('layouts.app')
@section('title', 'Nouvelle catégorie')
@section('page-title', '🏷️ Nouvelle Catégorie')

@section('content')

<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl shadow p-8">

        <a href="{{ route('categories.index') }}"
           class="text-sm text-gray-500 hover:text-blue-600 mb-6 inline-flex items-center gap-1">
            ← Retour
        </a>

        <h2 class="text-xl font-bold text-gray-800 mb-6 mt-2">Créer une catégorie</h2>

        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            <div class="space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="Ex: Transport, Ventes..."
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                  {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('name') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Choisir --</option>
                        <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>💸 Dépense</option>
                        <option value="revenue" {{ old('type') === 'revenue' ? 'selected' : '' }}>💰 Recette</option>
                    </select>
                    @error('type') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                {{-- Sélecteur de couleur avec Alpine --}}
                <div x-data="{ color: '{{ old('color', '#3B82F6') }}' }">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Couleur <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-3">
                        {{-- Aperçu de la couleur --}}
                        <span class="w-10 h-10 rounded-lg border border-gray-300"
                              :style="'background-color: ' + color"></span>
                        {{-- Input color natif --}}
                        <input type="color" name="color"
                               x-model="color"
                               value="{{ old('color', '#3B82F6') }}"
                               class="w-16 h-10 border border-gray-300 rounded-lg cursor-pointer">
                        {{-- Afficher la valeur hex --}}
                        <span x-text="color" class="text-sm text-gray-500 font-mono"></span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Cette couleur apparaîtra dans les graphiques</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description') }}</textarea>
                </div>

            </div>

            <hr class="my-6 border-gray-200">

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2.5 rounded-lg transition">
                    💾 Créer la catégorie
                </button>
                <a href="{{ route('categories.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-2.5 rounded-lg transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection