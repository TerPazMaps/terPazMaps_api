<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFeedbackStreetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'street_id' => ['required', 'exists:streets,id', 'unique:feedback_streets'],
            'street_condition_id' => ['required', 'exists:street_conditions,id'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'street_id.required' => 'O campo street_id é obrigatório.',
            'street_id.unique' => 'O campo street_id deve ser único na tabela feedback_streets.',
            'street_id.exists' => 'O campo street_id deve existir na tabela streets.',
            'street_condition_id.required' => 'O campo street_condition_id é obrigatório.',
            'street_condition_id.exists' => 'O campo street_condition_id deve existir na tabela street_conditions.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            "error" => [
                "status" => "422",
                "title" => "Unprocessable Entity",
                "detail" => $validator->errors(),
            ]
        ], 422));
    }
}
