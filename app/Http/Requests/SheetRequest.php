<?php

namespace App\Http\Requests;

class SheetRequest extends Request
{
    public function defaultRules(): array
    {
        return [
            'file' => 'required|file',
        ];
    }
}
