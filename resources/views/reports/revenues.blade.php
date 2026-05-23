<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { background: #166534; color: white; padding: 20px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; margin-bottom: 5px; }
        .total-box {
            background: #dcfce7; border: 1px solid #86efac;
            padding: 15px; border-radius: 6px;
            text-align: center; margin-bottom: 20px;
        }
        .total-box p.label { font-size: 11px; color: #666; }
        .total-box p.value { font-size: 20px; font-weight: bold; color: #166534; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #166534; color: white; }
        thead th { padding: 8px 10px; text-align: left; font-size: 11px; }
        tbody tr:nth-child(even) { background: #f0fdf4; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        .amount-green { color: #166534; font-weight: bold; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>

    <div class="header">
        <h1>💰 ExpenseFlow — Rapport des Revenues</h1>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <div class="total-box">
        <p class="label">Total des Revenues</p>
        <p class="value">{{ number_format($totalGeneral, 0, ',', ' ') }} FCFA</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Date</th>
                <th>Enregistré par</th>
                <th>Montant (FCFA)</th>
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
                <td class="amount-green">{{ number_format($revenue->amount, 0, ',', ' ') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding: 20px; color:#999;">
                    Aucune revenue trouvée
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