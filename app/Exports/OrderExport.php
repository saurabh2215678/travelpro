<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromArray, WithHeadings {

    use Exportable;

    protected $productsArr;
    protected $headings;

    public function __construct($productsArr, $headings) {
        $this->productsArr = $productsArr;
        $this->headings = $headings;
    }

    public function array(): array {

        $productsArr = $this->productsArr;
        return $productsArr;
    }

    public function headings(): array {
        $headings = $this->headings;

        return $headings;
    }
}