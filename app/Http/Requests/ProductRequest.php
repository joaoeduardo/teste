<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;

class ProductRequest extends Request
{
    public function defaultRules(): array
    {
        return Arr::dot([
            'data' => [
                'attributes' => [
                    'name'          => 'string',
                    'free_shipping' => 'boolean',
                    'description'   => 'string',
                    'price'         => 'integer',
                ]
            ]
        ]);
    }
}
