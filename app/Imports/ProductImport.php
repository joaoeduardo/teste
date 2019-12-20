<?php

namespace App\Imports;

use App\Models\Product;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $product = new Product();

        $product->id            = (int)    $row['lm'];
        $product->name          = (string) $row['name'];
        $product->free_shipping = (bool)   $row['free_shipping'];
        $product->description   = (bool)   $row['description'];
        $product->price         = (int)    $row['price'];

        $product->save();

        return $product;
    }

    public function headingRow(): int
    {
        return 3;
    }
}
