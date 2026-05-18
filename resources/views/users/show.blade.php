@extends('layouts.app')
@section('title', 'Profil utilisateur')
@section('page-title', '👥 Profil Utilisateur')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="mb-4">
        <a href="{{ route('users.index') }}"
           class="text-sm text-gray-500 hover:text-blue-600 inline-flex items-center gap-1">
            ← Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        {{-- En-tête profil --}}
        <div class="p-8 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
            <div class="flex items-center gap-5">

                {{-- Avatar grande taille --}}
                <div class="w-20 h-20 rounded-full bg-white bg-opacity-20
                            flex items-center justify-center text-4xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div>
                    <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                    <p class="text-blue-200">{{ $user->email }}</p>
                    <div class="mt-2">
                        @foreach($user->roles as $role)
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                         bg-white bg-opacity-20 text-white">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistiques de l'utilisateur --}}
        <div class="grid grid-cols-3 divide-x divide-gray-100 border-b border-gray-100">

            <div class="p-6 text-center">
                <p class="text-2xl font-bold text-red-600">
                    {{ $user->expenses()->count() }}
                </p>
                <p class="text-sm text-gray-500 mt-1">Dépenses</p>
            </div>

            <div class="p-6 text-center">
                <p class="text-2xl font-bold text-green-600">
                    {{ $user->revenues()->count() }}
                </p>
                <p class="text-sm text-gray-500 mt-1">Revenues</p>
            </div>

            <div class="p-6 text-center">
                <p class="text-2xl font-bold text-gray-800">
                    {{ $user->created_at->diffForHumans() }}
                </p>
                {{-- diffForHumans() = "il y a 2 jours", "il y a 1 mois" --}}
                <p class="text-sm text-gray-500 mt-1">Inscrit</p>
            </div>

        </div>

        {{-- Permissions --}}
        <div class="p-6">
            <h3 class="font-semibold text-gray-700 mb-3">Permissions accordées</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($user->getAllPermissions() as $permission)
                    {{-- getAllPermissions() = toutes les permissions via le rôle --}}
                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">
                        {{ $permission->name }}
                    </span>
                @endforeach
            </div>
        </div>

        {{-- Actions --}}
        <div class="px-6 pb-6 flex gap-3 border-t border-gray-100 pt-4">
            <a href="{{ route('users.edit', $user) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold
                      px-5 py-2 rounded-lg transition">
                ✏️ Modifier
            </a>
            @if($user->id !== auth()->id())
            <form method="POST" action="{{ route('users.destroy', $user) }}"
                  onsubmit="return confirm('Supprimer cet utilisateur ?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold
                               px-5 py-2 rounded-lg transition">
                    🗑 Supprimer
                </button>
            </form>
            @endif
        </div>

    </div>
</div>

@endsection