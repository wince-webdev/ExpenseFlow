@extends('layouts.guest')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900
            flex items-center justify-center p-4">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl mb-4 shadow-lg">
                <span class="text-3xl">💰</span>
            </div>
            <h1 class="text-3xl font-bold text-white">ExpenseFlow</h1>
            <p class="text-gray-400 mt-1">Réinitialisation du mot de passe</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">

            <h2 class="text-xl font-bold text-gray-800 mb-2 text-center">
                Mot de passe oublié ?
            </h2>
            <p class="text-sm text-gray-500 text-center mb-6">
                Entrez votre email. Nous vous enverrons un lien de réinitialisation.
            </p>

            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm text-center">
                    ✅ {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm">
                    ❌ {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Adresse email
                    </label>
                    <input type="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="votre@email.com"
                           required autofocus
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition text-sm">
                    📧 Envoyer le lien de réinitialisation
                </button>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                        ← Retour à la connexion
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection