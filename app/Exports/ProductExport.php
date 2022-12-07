<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ProductExport implements FromCollection,WithHeadings
{
    public $products;
    public function __construct($products)
    {
        $this->products=$products;
    }
    public function collection()
    {
        return $this->products;
    }
    public function headings(): array
    {
        return [
            'id',
            'Name',
            'Price',
            'Picture',
            'Create',
        ];
    }
}
