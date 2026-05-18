@extends('layouts.app')
@section('title', 'Utilisateurs')
@section('page-title', '👥 Gestion des Utilisateurs')

@section('content')

<div class="flex justify-between items-center mb-6">
    <p class="text-gray-500 text-sm">{{ $users->total() }} utilisateur(s)</p>
    <a href="{{ route('users.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg transition">
        ＋ Nouvel utilisateur
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 text-gray-600 font-semibold">Utilisateur</th>
                <th class="text-left px-6 py-3 text-gray-600 font-semibold">Rôle</th>
                <th class="text-left px-6 py-3 text-gray-600 font-semibold">Inscrit le</th>
                <th class="text-center px-6 py-3 text-gray-600 font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        {{-- Avatar initiale --}}
                        <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    @foreach($user->roles as $role)
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $role->name === 'admin' ? 'bg-purple-100 text-purple-700' :
                               ($role->name === 'comptable' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst($role->name) }}
                        </span>
                    @endforeach
                </td>
                <td class="px-6 py-4 text-gray-500">
                    {{ $user->created_at->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('users.show', $user) }}"
                           class="text-blue-600 hover:text-blue-800 text-xs px-2 py-1 rounded hover:bg-blue-50">
                            👁 Voir
                        </a>
                        <a href="{{ route('users.edit', $user) }}"
                           class="text-yellow-600 hover:text-yellow-800 text-xs px-2 py-1 rounded hover:bg-yellow-50">
                            ✏️ Modifier
                        </a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('users.destroy', $user) }}"
                              onsubmit="return confirm('Supprimer cet utilisateur ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 hover:text-red-800 text-xs px-2 py-1 rounded hover:bg-red-50">
                                🗑 Supprimer
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-12 text-gray-400">Aucun utilisateur</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection