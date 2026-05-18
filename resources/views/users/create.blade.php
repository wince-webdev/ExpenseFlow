@extends('layouts.app')
@section('title', 'Nouvel utilisateur')
@section('page-title', '👥 Nouvel Utilisateur')

@section('content')

<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-xl shadow p-8">

        <a href="{{ route('users.index') }}"
           class="text-sm text-gray-500 hover:text-blue-600 mb-6 inline-flex items-center gap-1">
            ← Retour
        </a>

        <h2 class="text-xl font-bold text-gray-800 mb-6 mt-2">Créer un utilisateur</h2>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nom complet <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="Ex: Jean Dupont"
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                  {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('name') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="jean@exemple.com"
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                  {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('email') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Rôle <span class="text-red-500">*</span></label>
                    <select name="role"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Choisir un rôle --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Mot de passe <span class="text-red-500">*</span></label>
                    <input type="password" name="password"
                           placeholder="Minimum 8 caractères"
                           class="w-full border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                                  {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('password') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Confirmer le mot de passe <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation"
                           placeholder="Répéter le mot de passe"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

            </div>

            <hr class="my-6 border-gray-200">

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2.5 rounded-lg transition">
                    💾 Créer l'utilisateur
                </button>
                <a href="{{ route('users.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-2.5 rounded-lg transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

@endsection