{{-- On hérite du layout principal --}}
@extends('layouts.app')

{{-- On définit le titre --}}
@section('title', 'Dashboard')
@section('page-title', '📊 Tableau de bord')

{{-- On injecte notre contenu dans @yield('content') du layout --}}
@section('content')

{{-- ===== CARTES STATISTIQUES ===== --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    {{-- Carte Dépenses --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
        <p class="text-sm text-gray-500 mb-1">Dépenses du mois</p>
        <p class="text-2xl font-bold text-gray-800">
            {{-- number_format() formate le nombre avec des séparateurs --}}
            {{ number_format($totalDepensesMois, 0, ',', ' ') }} FCFA
        </p>
        <p class="text-xs text-red-500 mt-1">Dépenses approuvées</p>
    </div>

    {{-- Carte Revenues --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
        <p class="text-sm text-gray-500 mb-1">Revenues du mois</p>
        <p class="text-2xl font-bold text-gray-800">
            {{ number_format($totalRecettesMois, 0, ',', ' ') }} FCFA
        </p>
        <p class="text-xs text-green-500 mt-1">Total encaissé</p>
    </div>

    {{-- Carte Bénéfice --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4
                {{ $beneficeNet >= 0 ? 'border-blue-500' : 'border-orange-500' }}">
        <p class="text-sm text-gray-500 mb-1">Bénéfice net</p>
        <p class="text-2xl font-bold {{ $beneficeNet >= 0 ? 'text-blue-600' : 'text-orange-600' }}">
            {{ number_format($beneficeNet, 0, ',', ' ') }} FCFA
        </p>
        <p class="text-xs text-gray-400 mt-1">Revenues - Dépenses</p>
    </div>

    {{-- Carte En attente --}}
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
        <p class="text-sm text-gray-500 mb-1">En attente</p>
        <p class="text-2xl font-bold text-gray-800">{{ $depensesEnAttente }}</p>
        <p class="text-xs text-yellow-600 mt-1">Dépenses à approuver</p>
    </div>

    <a href="{{ route('reports.monthly.pdf') }}"
        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition flex items-center gap-2"
        target="_blank">
            📄 Rapport mensuel PDF
    </a>

</div>

{{-- ===== GRAPHIQUE ===== --}}
<div class="bg-white rounded-xl shadow p-6 mb-8">
    <h2 class="text-lg font-semibold text-gray-700 mb-4">
        📈 Évolution sur 12 mois
    </h2>
    <canvas id="graphiqueMensuel" height="100"></canvas>
</div>

{{-- ===== TABLEAUX RÉCENTS ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Dernières dépenses --}}
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-700">💸 Dernières dépenses</h2>
            <a href="{{ route('expenses.index') }}" class="text-sm text-blue-600 hover:underline">
                Voir tout →
            </a>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 border-b">
                    <th class="text-left py-2">Titre</th>
                    <th class="text-right py-2">Montant</th>
                    <th class="text-right py-2">Statut</th>
                </tr>
            </thead>
            <tbody>
                {{-- @forelse = foreach avec cas "vide" --}}
                @forelse($dernieresDepenses as $expense)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-2">
                            <p class="font-medium text-gray-800">{{ $expense->title }}</p>
                            <p class="text-xs text-gray-400">{{ $expense->category->name }}</p>
                        </td>
                        <td class="py-2 text-right font-semibold text-red-600">
                            {{ number_format($expense->amount, 0, ',', ' ') }}
                        </td>
                        <td class="py-2 text-right">
                            {{-- Badge de statut --}}
                            @if($expense->status === 'approved')
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Approuvé</span>
                            @elseif($expense->status === 'pending')
                                <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">En attente</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">Rejeté</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-gray-400 py-4">Aucune dépense</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Dernières revenues --}}
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-700">💰 Dernières revenues</h2>
            <a href="{{ route('revenues.index') }}" class="text-sm text-blue-600 hover:underline">
                Voir tout →
            </a>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 border-b">
                    <th class="text-left py-2">Titre</th>
                    <th class="text-right py-2">Montant</th>
                    <th class="text-right py-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dernieresRecettes as $revenue)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-2">
                            <p class="font-medium text-gray-800">{{ $revenue->title }}</p>
                            <p class="text-xs text-gray-400">{{ $revenue->category->name }}</p>
                        </td>
                        <td class="py-2 text-right font-semibold text-green-600">
                            {{ number_format($revenue->amount, 0, ',', ' ') }}
                        </td>
                        <td class="py-2 text-right text-gray-500">
                            {{-- format() = Carbon formate la date --}}
                            {{ $revenue->revenue_date->format('d/m/Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-gray-400 py-4">Aucune revenue</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection

@push('scripts')
<script>
    const mois     = {!! json_encode($graphiqueMois) !!};
    const depenses = {!! json_encode($graphiqueDepenses) !!};
    const recettes = {!! json_encode($graphiqueRecettes) !!};

    const ctx = document.getElementById('graphiqueMensuel').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: mois,
            datasets: [
                {
                    label: 'Dépenses (FCFA)',
                    data: depenses,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Revenues (FCFA)',
                    data: recettes,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush