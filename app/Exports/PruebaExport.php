<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;

class PruebaExport implements FromView, WithTitle, WithEvents {

    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function view(): View {
        return view('adblue.reportes.excel.vw_prueba', [
            'datos' => $this->data
        ]);
    }

    public function title(): string {
        return 'EVALUACIONES';
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->getSheet()->autoSize();
                $event->getSheet()->getDelegate()->getStyle('A3:D3')
                        ->getAlignment();
            },
            AfterSheet::class => function(AfterSheet $event) {
                // All headers - set font size to 14
                $cellRange = 'A1:W1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

                // Apply array of styles to B2:G8 cell range
                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FFFF0000'],
                        ]
                    ]
                ];
                $event->sheet->getDelegate()->getStyle('B2:G8')->applyFromArray($styleArray);

                // Set first row to height 20
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(20);

                // Set A1:D4 range to wrap text in cells
                $event->sheet->getDelegate()->getStyle('A1:D4')
                        ->getAlignment()->setWrapText(true);
            },
            BeforeExport::class => function(BeforeExport $event) {
                $event->getWriter()->getDelegate()
                        ->getProperties()
                        ->setCreator("Enzo Edir Velásquez Lobatón")
                        ->setLastModifiedBy("Enzo Edir Velásquez Lobatón")
                        ->setTitle("SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES")
                        ->setSubject("SELECCIÓN, EVALUACIÓN Y REEVALUACIÓN DE PROVEEDORES")
                        ->setDescription(
                                "EVALUACIÓN DE PROVEEDORES"
                        )
                ;
            }
        ];
    }

}
