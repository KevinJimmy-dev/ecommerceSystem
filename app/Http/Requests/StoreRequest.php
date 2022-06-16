<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:4',
                'max:20'
            ],
            'description' => [
                'nullable',
                'string',
                'min:3',
                'max:100'
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'É necessário um nome!',
            'name.min' => 'O número minimo de caracteres é 4!',
            'name.max' => 'O número máximo de caracteres é 20!',
            'description.min' => 'O número minimo de caracteres é 3!',
            'description.max' => 'O número máximo de caracteres é 100!',
        ];
    }
}
