<?php

namespace App\Exports;

use App\Models\Ferme;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
class OperationExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Ferme::select(
            DB::raw('DATE(ferme.date) as operation_date'),
            DB::raw('SUM(ferme.dotation) as sum_dotation'),
            DB::raw('SUM(ferme.montant) as sum_montant'),
            'charges.libelle as charge_name'
        )
        ->leftJoin('charges', 'ferme.charge_id', '=', 'charges.id') 
        ->join('comptabilite as c', 'c.id', '=', 'ferme.idcomptabilite')
        ->where('c.status', '=', 1)
        ->groupBy(DB::raw('DATE(ferme.date)'), 'charges.libelle') 
        ->orderBy('operation_date', 'desc')
        ->get(); // ✅ this is required
    }
    public function headings(): array
    {
        return [
            ['📊 Bilan des opérations de la ferme'], // Title in A1
            [''],                                    // Empty row (A2)
            ['Date', 'Dotation', 'Montant', 'Dépense'], // Table headers (A3:D3)
        ];
    }

    public function title(): string
    {
        return 'Opérations Ferme';
    }

    public function styles(Worksheet $sheet)
    {
        $dataCount = $this->collection()->count();
        $lastRow = 3 + $dataCount; // Title row (1) + empty row (2) + headers (3) + data

        // Merge and style title row
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style table headers (row 3)
        $sheet->getStyle('A3:D3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFCCE5FF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style data rows (starting from row 4)
        $sheet->getStyle("A4:D{$lastRow}")->applyFromArray([
            'font' => ['size' => 11],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        // Auto size columns
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
