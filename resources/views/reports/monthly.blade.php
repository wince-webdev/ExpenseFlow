<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #333; }

        .header {
            background: #1e3a5f;
            color: white;
            padding: 15px 20px;
            margin-bottom: 15px;
        }
        .header h1 { font-size: 16px; margin-bottom: 4px; }
        .header p { font-size: 10px; opacity: 0.8; }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .stats-table td {
            width: 33%;
            padding: 10px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        .stats-table .label { font-size: 10px; color: #666; margin-bottom: 4px; }
        .stats-table .value { font-size: 14px; font-weight: bold; }
        .bg-red   { background: #fee2e2; }
        .bg-green { background: #dcfce7; }
        .bg-blue  { background: #dbeafe; }
        .text-red   { color: #dc2626; }
        .text-green { color: #166534; }
        .text-blue  { color: #1d4ed8; }

        h2 {
            font-size: 12px;
            margin: 15px 0 8px;
            padding: 6px 10px;
            background: #f1f5f9;
            border-left: 4px solid #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        thead tr { background: #1e3a5f; color: white; }
        thead th {
            padding: 8px 10px;
            text-align: center;
            font-size: 10px;
            border: 1px solid #2d4a6f;
            font-weight: bold;
            text-transform: uppercase;
        }
        tbody td {
            padding: 7px 10px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
            text-align: center;
        }
        tbody td:nth-child(2) { text-align: left; }
        tbody td:nth-child(3) { text-align: left; }
        tbody tr:nth-child(even) { background: #f8fafc; }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-approved { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-pending  { background: #fef9c3; color: #713f12; border: 1px solid #fde047; }
        .badge-rejected { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        .amount-red   { color: #dc2626; font-weight: bold; }
        .amount-green { color: #166534; font-weight: bold; }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 style="text-align: center">EXPENSEFLOW — RAPPORT MENSUEL : {{ strtoupper(\Carbon\Carbon::parse($mois)->translatedFormat('F Y')) }}</h1>
        <p>Généré le {{ now()->format('d/m/Y a H:i') }}</p>
    </div>

    {{-- Statistiques en tableau (DomPDF supporte mieux les tables que flexbox) --}}
    <table class="stats-table">
        <tr>
            <td class="bg-red">
                <div class="label">Total Depenses</div>
                <div class="value text-red">{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</div>
            </td>
            <td class="bg-green">
                <div class="label">Total Revenues</div>
                <div class="value text-green">{{ number_format($totalRevenues, 0, ',', ' ') }} FCFA</div>
            </td>
            <td class="bg-blue">
                <div class="label">Benefice Net</div>
                <div class="value {{ $benefice >= 0 ? 'text-blue' : 'text-red' }}">
                    {{ number_format($benefice, 0, ',', ' ') }} FCFA
                </div>
            </td>
        </tr>
    </table>

    <h2 style="text-align: center">Depenses du mois</h2>
    <table>
        <thead>
            <tr>
                <th style="width:4%">#</th>
                <th style="width:28%; text-align:left">Titre</th>
                <th style="width:18%; text-align:left">Categorie</th>
                <th style="width:12%">Date</th>
                <th style="width:15%">Soumis par</th>
                <th style="width:13%">Montant</th>
                <th style="width:10%">Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $expense)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="text-align:left">{{ $expense->title }}</td>
                <td style="text-align:left">{{ $expense->category->name }}</td>
                <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
                <td>{{ $expense->user->name }}</td>
                <td class="amount-red">{{ number_format($expense->amount, 0, ',', ' ') }}</td>
                <td>
                    @if($expense->status === 'approved')
                        <span class="badge badge-approved">Approuvé</span>
                    @elseif($expense->status === 'pending')
                        <span class="badge badge-pending">En attente</span>
                    @else
                        <span class="badge badge-rejected">Rejete</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:15px; color:#999;">
                    Aucune depense ce mois
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h2 style="text-align: center">Revenues du mois</h2>
    <table>
        <thead>
            <tr>
                <th style="width:4%">#</th>
                <th style="width:30%; text-align:left">Titre</th>
                <th style="width:20%; text-align:left">Categorie</th>
                <th style="width:13%">Date</th>
                <th style="width:18%">Enregistre par</th>
                <th style="width:15%">Montant</th>
            </tr>
        </thead>
        <tbody>
            @forelse($revenues as $revenue)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="text-align:left">{{ $revenue->title }}</td>
                <td style="text-align:left">{{ $revenue->category->name }}</td>
                <td>{{ $revenue->revenue_date->format('d/m/Y') }}</td>
                <td>{{ $revenue->user->name }}</td>
                <td class="amount-green">{{ number_format($revenue->amount, 0, ',', ' ') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:15px; color:#999;">
                    Aucune revenue ce mois
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        ExpenseFlow — Rapport mensuel généré automatiquement le {{ now()->format('d/m/Y') }}
    </div>

</body>
</html>