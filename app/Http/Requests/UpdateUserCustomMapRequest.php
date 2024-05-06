<?php

namespace App\Http\Requests;

// use App\Utils\GeoJsonValidator;

use App\Http\Controllers\ServicesController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserCustomMapRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'geometry' => ['required', 'array'],
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
            'name.required' => 'O campo nome é obrigatório.',
            'geometry.required' => 'O campo geometry é obrigatório.',
            'geometry.array' => 'O campo geometry deve ser um array.',
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
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }

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
        $validateGeojson = ServicesController::GeoJsonValidator($this->geojson);
        if ($validateGeojson !== true) {
            $this->getValidatorInstance();
            $this->validator->errors()->add($validateGeojson['type'], $validateGeojson['message']);
            throw new HttpResponseException(response()->json(['errors' => $this->validator->errors()], 422));
        }
    }
}
