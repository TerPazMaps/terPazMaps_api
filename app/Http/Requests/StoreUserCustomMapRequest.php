<?php

namespace App\Http\Requests;

use App\Services\GeospatialService;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\ServicesController;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class StoreUserCustomMapRequest extends FormRequest
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
            'geojson' => ['required'],
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
            'geojson.required' => 'O campo geojson é obrigatório.',
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

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {

        // Convertendo a string JSON fornecida em um array associativo
        $geojson = json_decode($this->geojson, true);

        // Extração dos dados do GeoJSON
        $features = $geojson['features'][0];
        $properties = $features['properties'];
        $geometry = $features['geometry'];

        // Definindo os dados de entrada para validação
        $this->merge([
            'name' => $properties['Nome'],
            'geometry' => $geometry['coordinates'],
        ]);

        // Verificando a validade do GeoJSON
        $validateGeojson = GeospatialService::GeoJsonValidator($this->geojson);
        if ($validateGeojson !== true) {
            $this->getValidatorInstance();
            $this->validator->errors()->add($validateGeojson['type'], $validateGeojson['message']);
            throw new HttpResponseException(response()->json([
                "error" => [
                    "status" => "422",
                    "title" => "Unprocessable Entity",
                    "detail" => $this->validator->errors(),
                ]
            ], 422));
        }
    }
}
