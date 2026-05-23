<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { background: #1e3a5f; color: white; padding: 20px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; }
        .stats { display: flex; gap: 10px; margin-bottom: 20px; }
        .stat { flex: 1; padding: 12px; border-radius: 6px; text-align: center; }
        .stat.red    { background: #fee2e2; }
        .stat.green  { background: #dcfce7; }
        .stat.blue   { background: #dbeafe; }
        .stat p.label { font-size: 10px; color: #555; }
        .stat p.value { font-size: 15px; font-weight: bold; margin-top: 3px; }
        h2 { font-size: 13px; margin: 15px 0 8px; padding: 5px 10px;
             background: #f1f5f9; border-left: 4px solid #1e40af; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        thead tr { background: #1e3a5f; color: white; }
        thead th { padding: 7px 8px; text-align: left; font-size: 10px; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        .red   { color: #dc2626; font-weight: bold; }
        .green { color: #166534; font-weight: bold; }
        .footer { margin-top: 15px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>

    <div class="header">
        <h1>ExpenseFlow — Rapport Mensuel : {{ \Carbon\Carbon::parse($mois)->translatedFormat('F Y') }}</h1>
        {{-- <h1>💰 ExpenseFlow — Rapport Mensuel : {{ \Carbon\Carbon::parse($mois)->translatedFormat('F Y') }}</h1> --}}
        <p style="font-size:10px; opacity:0.8;">Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <div class="stats">
        <div class="stat red">
            <p class="label">Total Dépenses</p>
            <p class="value red">{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</p>
        </div>
        <div class="stat green">
            <p class="label">Total Revenues</p>
            <p class="value green">{{ number_format($totalRevenues, 0, ',', ' ') }} FCFA</p>
        </div>
        <div class="stat blue">
            <p class="label">Bénéfice Net</p>
            <p class="value" style="color: {{ $benefice >= 0 ? '#1d4ed8' : '#dc2626' }}">
                {{ number_format($benefice, 0, ',', ' ') }} FCFA
            </p>
        </div>
    </div>

    {{-- <h2>💸 Dépenses du mois</h2> --}}
    <h2>DEPENSES DU MOIS</h2>
    <table>
        <thead>
            <tr>
                <th>#</th><th>Titre</th><th>Catégorie</th>
                <th>Date</th><th>Par</th><th>Montant</th><th>Statut</th>
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
                <td class="red">{{ number_format($expense->amount, 0, ',', ' ') }}</td>
                <td>{{ ucfirst($expense->status) }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:#999;padding:10px;">Aucune dépense</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- <h2>💰 Revenues du mois</h2> --}}
    <h2>REVENUES DU MOIS</h2>
    <table>
        <thead>
            <tr>
                <th>#</th><th>Titre</th><th>Catégorie</th>
                <th>Date</th><th>Par</th><th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @forelse($revenues as $revenue)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $revenue->title }}</td>
                <td>{{ $revenue->category->name }}</td>
                <td>{{ $revenue->revenue_date->format('d/m/Y') }}</td>
                <td>{{ $revenue->user->name }}</td>
                <td class="green">{{ number_format($revenue->amount, 0, ',', ' ') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#999;padding:10px;">Aucune revenue</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        ExpenseFlow — Rapport mensuel généré automatiquement
    </div>

</body>
</html>