<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExpensesExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle
{
    // On peut passer des filtres au constructeur
    protected $status;
    protected $dateDebut;
    protected $dateFin;

    public function __construct($status = null, $dateDebut = null, $dateFin = null)
    {
        $this->status    = $status;
        $this->dateDebut = $dateDebut;
        $this->dateFin   = $dateFin;
    }

    // FromCollection = retourner les données sous forme de collection
    public function collection()
    {
        $query = Expense::with(['category', 'user']);

        if ($this->status) {
            $query->where('status', $this->status);
        }
        if ($this->dateDebut) {
            $query->where('expense_date', '>=', $this->dateDebut);
        }
        if ($this->dateFin) {
            $query->where('expense_date', '<=', $this->dateFin);
        }

        return $query->latest()->get();
    }

    // WithHeadings = définir les en-têtes des colonnes
    public function headings(): array
    {
        return [
            '#',
            'Titre',
            'Categorie',
            'Date',
            'Soumis par',
            'Montant (FCFA)',
            'Statut',
            'Notes',
            'Cree le',
        ];
    }

    // WithMapping = transformer chaque ligne de données
    // $expense = un objet Expense
    public function map($expense): array
    {
        static $iteration = 0;
        $iteration++;

        return [
            $iteration,
            $expense->title,
            $expense->category->name,
            $expense->expense_date->format('d/m/Y'),
            $expense->user->name,
            $expense->amount,
            match($expense->status) {
                'approved' => 'Approuve',
                'pending'  => 'En attente',
                'rejected' => 'Rejete',
                default    => $expense->status,
            },
            $expense->notes ?? '',
            $expense->created_at->format('d/m/Y'),
        ];
    }

    // WithStyles = styliser les cellules
    public function styles(Worksheet $sheet)
    {
        return [
            // Ligne 1 = en-têtes → fond bleu, texte blanc, gras
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E40AF'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    // WithTitle = nom de l'onglet dans Excel
    public function title(): string
    {
        return 'Depenses';
    }
}