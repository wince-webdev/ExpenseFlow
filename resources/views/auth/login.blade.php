@extends('layouts.guest')

@section('content')

{{-- <div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900 --}}

<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-blue-950
            flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16
                        bg-blue-600 rounded-2xl mb-4 shadow-lg">
                <span class="text-3xl">💰</span>
            </div>
            <h1 class="text-3xl font-bold text-white">ExpenseFlow</h1>
            <p class="text-gray-400 mt-1">Gestion financière d'entreprise</p>
        </div>

        {{-- Carte de connexion --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">

            <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">
                Connexion à votre compte
            </h2>

            {{-- Message d'erreur global --}}
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm">
                    ❌ Email ou mot de passe incorrect.
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="space-y-4">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Adresse email
                        </label>
                        <input type="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="votre@email.com"
                               required autofocus
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500
                                      {{ $errors->has('email') ? 'border-red-400' : '' }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Mot de passe
                        </label>
                        <input type="password" name="password"
                               placeholder="••••••••"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="remember"
                                   class="rounded border-gray-300 text-blue-600">
                            Se souvenir de moi
                        </label>

                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-blue-600 hover:underline">
                            Mot de passe oublié ?
                        </a>
                        @endif
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold
                                   py-3 rounded-lg transition text-sm mt-2">
                        Se connecter →
                    </button>

                </div>
            </form>

            {{-- Comptes de test --}}
            <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">
                    Comptes de démonstration
                </p>
                <div class="space-y-1 text-xs text-gray-600">
                    <p>👑 <strong>Admin</strong> : admin@smartexpense.com</p>
                    <p>📊 <strong>Comptable</strong> : comptable@smartexpense.com</p>
                    <p>👤 <strong>Employé</strong> : employe@smartexpense.com</p>
                    <p class="text-gray-400 mt-1">Mot de passe : <code class="bg-gray-200 px-1 rounded">password123</code></p>
                </div>
            </div>

        </div>

        <p class="text-center text-gray-500 text-xs mt-6">
            © 2026 ExpenseFlow — Gestion financière
        </p>
    </div>
</div>

@endsection