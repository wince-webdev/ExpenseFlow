@extends('layouts.app')
@section('title', 'Modifier la dépense')
@section('page-title', '✏️ Modifier la Dépense')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow p-8">

        <a href="{{ route('expenses.index') }}"
           class="text-sm text-gray-500 hover:text-blue-600 mb-6 inline-flex items-center gap-1">
            ← Retour à la liste
        </a>

        <h2 class="text-xl font-bold text-gray-800 mb-6 mt-2">
            Modifier : <span class="text-blue-600">{{ $expense->title }}</span>
        </h2>

        <form method="POST" action="{{ route('expenses.update', $expense) }}"
              enctype="multipart/form-data">
            @csrf
            {{-- @method('PUT') indique à Laravel que c'est une requête PUT --}}
            {{-- HTML ne supporte que GET et POST --}}
            {{-- Laravel lit ce champ caché et traite comme PUT → appelle update() --}}
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Titre <span class="text-red-500">*</span>
                    </label>
                    {{-- old('title', $expense->title) --}}
                    {{-- Si validation échoue → old('title') --}}
                    {{-- Sinon → $expense->title (valeur actuelle en base) --}}
                    <input type="text"
                           name="title"
                           value="{{ old('title', $expense->title) }}"
                           class="w-full border rounded-lg px-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500
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
                        <input type="number"
                               name="amount"
                               value="{{ old('amount', $expense->amount) }}"
                               min="0" step="1"
                               class="w-full border rounded-lg pl-14 pr-4 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
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
                    {{-- $expense->expense_date->format('Y-m-d') --}}
                    {{-- expense_date est casté en Carbon (voir Model) --}}
                    {{-- ->format('Y-m-d') = format attendu par input type="date" --}}
                    <input type="date"
                           name="expense_date"
                           value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('expense_date')
                        <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{-- old() en priorité, sinon compare avec la catégorie actuelle --}}
                                {{ old('category_id', $expense->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Statut <span class="text-red-500">*</span>
                    </label>
                    <select name="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending"  {{ old('status', $expense->status) === 'pending'  ? 'selected' : '' }}>⏳ En attente</option>
                        <option value="approved" {{ old('status', $expense->status) === 'approved' ? 'selected' : '' }}>✅ Approuvé</option>
                        <option value="rejected" {{ old('status', $expense->status) === 'rejected' ? 'selected' : '' }}>❌ Rejeté</option>
                    </select>
                </div>

                {{-- Justificatif actuel --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Justificatif
                    </label>

                    {{-- Si un justificatif existe déjà, l'afficher --}}
                    @if($expense->attachment)
                        <div class="mb-3 p-3 bg-blue-50 rounded-lg flex items-center gap-3">
                            <span class="text-blue-600">📎</span>
                            <a href="{{ Storage::url($expense->attachment) }}"
                               target="_blank"
                               class="text-blue-600 hover:underline text-sm">
                                Voir le justificatif actuel
                            </a>
                            <span class="text-xs text-gray-400">(un nouveau fichier remplacera l'ancien)</span>
                        </div>
                    @endif

                    <div x-data="{ fileName: '' }"
                         class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition">
                        <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png"
                               class="hidden" id="attachment"
                               @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                        <label for="attachment" class="cursor-pointer">
                            <p class="text-gray-500 text-sm">📎 Cliquez pour changer le fichier</p>
                            <p x-show="!fileName" class="text-xs text-gray-400 mt-1">PDF, JPG, PNG</p>
                            <p x-show="fileName" x-text="'✅ ' + fileName" class="text-xs text-green-600 mt-1 font-medium"></p>
                        </label>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                     focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes', $expense->notes) }}</textarea>
                </div>

            </div>
            <br>

            <hr class="my-6 border-gray-200">
            <br>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Mettre à jour
                </button>
                <a href="{{ route('expenses.index') }}"
                   class="bg-red-100 hover:bg-red-200 text-red-700 font-semibold px-6 py-2 rounded-lg transition">
                    Annuler
                </a>
            </div>

        </form>
    </div>
</div>

@endsection