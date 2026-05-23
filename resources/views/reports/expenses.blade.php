<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }

        .header {
            background: #1e40af;
            color: white;
            padding: 20px;
            margin-bottom: 20px;
        }
        .header h1 { font-size: 20px; margin-bottom: 5px; }
        .header p { font-size: 11px; opacity: 0.8; }

        .stats {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-box {
            flex: 1;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
        }
        .stat-box.green  { background: #dcfce7; border: 1px solid #86efac; }
        .stat-box.yellow { background: #fef9c3; border: 1px solid #fde047; }
        .stat-box.red    { background: #fee2e2; border: 1px solid #fca5a5; }
        .stat-box p.label { font-size: 10px; color: #666; margin-bottom: 4px; }
        .stat-box p.value { font-size: 16px; font-weight: bold; }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead tr {
            background: #1e40af;
            color: white;
        }
        thead th {
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
        }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:hover { background: #eff6ff; }
        tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }

        .badge {
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge.approved { background: #dcfce7; color: #166534; }
        .badge.pending  { background: #fef9c3; color: #713f12; }
        .badge.rejected { background: #fee2e2; color: #991b1b; }

        .amount-red   { color: #dc2626; font-weight: bold; }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>💰 ExpenseFlow — Rapport des Dépenses</h1>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    {{-- Cartes statistiques --}}
    <div class="stats">
        <div class="stat-box green">
            <p class="label">Total Approuvé</p>
            <p class="value">{{ number_format($totalApprouve, 0, ',', ' ') }} FCFA</p>
        </div>
        <div class="stat-box yellow">
            <p class="label">En Attente</p>
            <p class="value">{{ number_format($totalEnAttente, 0, ',', ' ') }} FCFA</p>
        </div>
        <div class="stat-box red">
            <p class="label">Total Général</p>
            <p class="value">{{ number_format($totalGeneral, 0, ',', ' ') }} FCFA</p>
        </div>
    </div>

    {{-- Tableau --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Date</th>
                <th>Soumis par</th>
                <th>Montant (FCFA)</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $expense)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $expense->title }}</td>
                <td>{{ $expense->category->name }}</td>
                <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
                <td>{{ $expense->user->name }}</td>
                <td class="amount-red">{{ number_format($expense->amount, 0, ',', ' ') }}</td>
                <td>
                    <span class="badge {{ $expense->status }}">
                        @if($expense->status === 'approved') Approuvé
                        @elseif($expense->status === 'pending') En attente
                        @else Rejeté @endif
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding: 20px; color: #999;">
                    Aucune dépense trouvée
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        ExpenseFlow — Rapport généré automatiquement — {{ now()->format('d/m/Y') }}
    </div>

</body>
</html>