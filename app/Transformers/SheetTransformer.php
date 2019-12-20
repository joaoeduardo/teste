<?php

namespace App\Transformers;

use App\Models\Sheet;

class SheetTransformer extends Transformer
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Sheet $sheet)
    {
        return $sheet->getAttributes();
    }
}
