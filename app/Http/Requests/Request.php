<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return $this->prepare(
                    $this->defaultRules(),
                    $this->storeRules()
                );
            case 'PUT':
            case 'PATCH':
                return $this->prepare(
                    $this->defaultRules(),
                    $this->updateRules()
                );
        }
    }

    private function prepare(array ...$args): array
    {
        foreach ($args as &$rules) {
            foreach ($rules as &$rule) {
                $rule = explode('|', $rule);
            }
        }

        return array_merge_recursive(...$args);
    }

    public function defaultRules(): array
    {
        return [];
    }

    public function storeRules(): array
    {
        return [];
    }

    public function updateRules(): array
    {
        return [];
    }
}
