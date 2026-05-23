<?php

namespace App\Exports;

use App\Models\Revenue;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RevenuesExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle
{
    protected $dateDebut;
    protected $dateFin;

    public function __construct($dateDebut = null, $dateFin = null)
    {
        $this->dateDebut = $dateDebut;
        $this->dateFin   = $dateFin;
    }

    public function collection()
    {
        $query = Revenue::with(['category', 'user']);

        if ($this->dateDebut) {
            $query->where('revenue_date', '>=', $this->dateDebut);
        }
        if ($this->dateFin) {
            $query->where('revenue_date', '<=', $this->dateFin);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Titre',
            'Categorie',
            'Date',
            'Enregistre par',
            'Montant (FCFA)',
            'Notes',
            'Cree le',
        ];
    }

    public function map($revenue): array
    {
        static $iteration = 0;
        $iteration++;

        return [
            $iteration,
            $revenue->title,
            $revenue->category->name,
            $revenue->revenue_date->format('d/m/Y'),
            $revenue->user->name,
            $revenue->amount,
            $revenue->notes ?? '',
            $revenue->created_at->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF166534'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Revenues';
    }
}