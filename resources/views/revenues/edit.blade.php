@extends('layouts.app')
@section('title', 'Modifier revenue')
@section('page-title', '✏️ Modifier le Revenue')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow p-8">

        <a href="{{ route('revenues.index') }}"
           class="text-sm text-gray-500 hover:text-blue-600 mb-6 inline-flex items-center gap-1">
            ← Retour à la liste
        </a>

        <h2 class="text-xl font-bold text-gray-800 mb-6 mt-2">
            Modifier : <span class="text-green-600">{{ $revenue->title }}</span>
        </h2>

        <form method="POST" action="{{ route('revenues.update', $revenue) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $revenue->title) }}"
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500
                                  {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('title') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Montant (FCFA) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">FCFA</span>
                        <input type="number" name="amount" value="{{ old('amount', $revenue->amount) }}"
                               min="0" step="1"
                               class="w-full border rounded-lg pl-14 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 border-gray-300">
                    </div>
                    @error('amount') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="revenue_date"
                           value="{{ old('revenue_date', $revenue->revenue_date->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('revenue_date') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Catégorie <span class="text-red-500">*</span></label>
                    <select name="category_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id', $revenue->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none">{{ old('notes', $revenue->notes) }}</textarea>
                </div>

            </div>

            <hr class="my-6 border-gray-200">

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-2.5 rounded-lg transition">
                    Modifier
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