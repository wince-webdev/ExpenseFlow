@extends('layouts.app')
@section('title', 'Nouvelle dépense')
@section('page-title', '💸 Nouvelle Dépense')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow p-8">

        {{-- Lien retour --}}
        <a href="{{ route('expenses.index') }}"
           class="text-sm text-gray-500 hover:text-blue-600 mb-6 inline-flex items-center gap-1">
            ← Retour à la liste
        </a>

        <h2 class="text-xl font-bold text-gray-800 mb-6 mt-2">
            Informations de la dépense
        </h2>

        {{--
             FORMULAIRE
             enctype="multipart/form-data" = OBLIGATOIRE pour upload
             Sans ça, le fichier joint ne sera JAMAIS envoyé au serveur
         --}}
        <form method="POST" action="{{ route('expenses.store') }}"
              enctype="multipart/form-data">
            @csrf
            {{-- @csrf génère ce champ caché :
                 <input type="hidden" name="_token" value="abc123...">
                 Sans lui, Laravel rejette le formulaire (sécurité CSRF)
                 En Symfony : {{ csrf_token() }} ou géré par le Form Type --}}

            {{--
                 GRILLE 2 COLONNES
                 grid-cols-1        = 1 colonne sur mobile
                 md:grid-cols-2     = 2 colonnes sur desktop (≥768px)
                 gap-5              = espace de 1.25rem entre les cases
                 Tailwind calcule automatiquement la largeur de chaque colonne
             --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- CHAMP 1 : Titre (prend toute la largeur) --}}
                {{-- md:col-span-2 = occupe les 2 colonnes de la grille --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Titre <span class="text-red-500">*</span>
                    </label>
                    {{-- old('title') = si validation échoue, remet la valeur tapée --}}
                    {{-- Sans old(), le champ serait vide après une erreur --}}
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           placeholder="Ex: Achat carburant déplacement client"
                           class="w-full border rounded-lg px-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500
                                  {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    {{-- @error('title') = affiche le message d'erreur si ce champ a échoué --}}
                    {{-- $message = variable automatique contenant le texte de l'erreur --}}
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                    @enderror
                </div>

                {{-- CHAMP 2 : Montant (colonne gauche) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Montant (FCFA) <span class="text-red-500">*</span>
                    </label>
                    {{-- old('amount') : même principe --}}
                    <div class="relative">
                        {{-- Icône positionnée à gauche dans le champ --}}
                        {{-- relative sur parent + absolute sur icône = positionnement CSS --}}
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                            FCFA
                        </span>
                        <input type="number"
                               name="amount"
                               value="{{ old('amount') }}"
                               placeholder="0"
                               min="0"
                               step="1"
                               class="w-full border rounded-lg pl-14 pr-4 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      {{ $errors->has('amount') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    </div>
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                    @enderror
                </div>

                {{-- CHAMP 3 : Date (colonne droite) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Date de la dépense <span class="text-red-500">*</span>
                    </label>
                    {{-- now()->format('Y-m-d') = date du jour par défaut --}}
                    {{-- old('expense_date', now()->format('Y-m-d')) = --}}
                    {{-- si old existe → prend old, sinon → prend la date du jour --}}
                    <input type="date"
                           name="expense_date"
                           value="{{ old('expense_date', now()->format('Y-m-d')) }}"
                           class="w-full border rounded-lg px-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500
                                  {{ $errors->has('expense_date') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('expense_date')
                        <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                    @enderror
                </div>

                {{-- CHAMP 4 : Catégorie (colonne gauche) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    {{-- $categories vient du Controller : Category::where('type','expense')->get() --}}
                    <select name="category_id"
                            class="w-full border rounded-lg px-4 py-2.5 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500
                                   {{ $errors->has('category_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        <option value="">-- Choisir une catégorie --</option>
                        @foreach($categories as $cat)
                            {{-- old('category_id') == $cat->id ? 'selected' : '' --}}
                            {{-- Remet la catégorie sélectionnée si validation échoue --}}
                            <option value="{{ $cat->id }}"
                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                    @enderror
                </div>

                {{-- CHAMP 5 : Statut (colonne droite) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Statut <span class="text-red-500">*</span>
                    </label>
                    <select name="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending"  {{ old('status','pending') === 'pending'  ? 'selected' : '' }}>
                            ⏳ En attente
                        </option>
                        <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>
                            ✅ Approuvé
                        </option>
                        <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>
                            ❌ Rejeté
                        </option>
                    </select>
                </div>

                {{-- CHAMP 6 : Justificatif (toute la largeur) --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Justificatif
                        <span class="text-gray-400 font-normal">(PDF ou image, max 2MB)</span>
                    </label>
                    {{-- Zone d'upload stylisée --}}
                    {{-- x-data Alpine.js : gérer l'affichage du nom de fichier choisi --}}
                    <div x-data="{ fileName: '' }"
                         class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center
                                hover:border-blue-400 transition cursor-pointer">
                        <input type="file"
                               name="attachment"
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="hidden"
                               id="attachment"
                               {{-- @change = événement Alpine quand le fichier change --}}
                               {{-- $event.target.files[0].name = nom du fichier sélectionné --}}
                               @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                        {{-- label cliquable qui ouvre le sélecteur de fichier --}}
                        <label for="attachment" class="cursor-pointer">
                            <p class="text-gray-500 text-sm">
                                📎 Cliquez pour choisir un fichier
                            </p>
                            {{-- x-show="!fileName" = visible si aucun fichier choisi --}}
                            <p x-show="!fileName" class="text-xs text-gray-400 mt-1">
                                PDF, JPG, PNG acceptés
                            </p>
                            {{-- x-show="fileName" = visible quand un fichier est choisi --}}
                            {{-- x-text="fileName" = affiche le nom du fichier --}}
                            <p x-show="fileName"
                               x-text="'✅ ' + fileName"
                               class="text-xs text-green-600 mt-1 font-medium">
                            </p>
                        </label>
                    </div>
                    @error('attachment')
                        <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                    @enderror
                </div>

                {{-- CHAMP 7 : Notes (toute la largeur) --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Notes
                        <span class="text-gray-400 font-normal">(optionnel)</span>
                    </label>
                    <textarea name="notes"
                              rows="3"
                              placeholder="Remarques supplémentaires sur cette dépense..."
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                     focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes') }}</textarea>
                </div>

            </div>{{-- fin grid --}}
            <br>

            {{-- LIGNE DE SÉPARATION --}}
            <hr class="my-6 border-gray-200">
            <br>

            {{-- BOUTONS --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold
                               px-6 py-2 rounded-lg transition flex items-center gap-2">
                    Enregistrer la dépense
                </button>
                <a href="{{ route('expenses.index') }}"
                   class="bg-red-100 hover:bg-red-200 text-red-700 font-semibold
                          px-6 py-2 rounded-lg transition">
                    Annuler
                </a>
            </div>


        </form>
    </div>
</div>

@endsection