<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportesExport implements WithMultipleSheets
{
    use Exportable;
    private $rep_1;
    private $rep_2;
    private $rep_3;
    private $rep_4;
    private $rep_5;
    private $rep_6;

    public function __construct($rep_1,$rep_2,$rep_3,$rep_4,$rep_5,$rep_6) {
        $this->rep_1 = $rep_1;
        $this->rep_2 = $rep_2;
        $this->rep_3 = $rep_3;
        $this->rep_4 = $rep_4;
        $this->rep_5 = $rep_5;
        $this->rep_6 = $rep_6;
    }
    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        //for ($month = 1; $month <= 12; $month++) {
            $sheets[] = new ReporteExport_1($this->rep_1);
            $sheets[] = new ReporteExport_2($this->rep_2);
            $sheets[] = new ReporteExport_3($this->rep_3);
            $sheets[] = new ReporteExport_4($this->rep_4);
            $sheets[] = new ReporteExport_5($this->rep_5);
            $sheets[] = new ReporteExport_6($this->rep_6);
        //}

        return $sheets;
    }
}