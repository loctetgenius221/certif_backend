<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateDossierMedicauxRequest extends FormRequest
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
            'date' => 'nullable|date',
            'antecedents_medicaux' => 'nullable|string',
            'traitements' => 'nullable|string',
            'notes_observations' => 'nullable|string',
            'intervention_chirurgicale' => 'nullable|string',
            'info_sup' => 'nullable|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['success' => false, 'errors' => $validator->errors()], 422)
        );
    }
}
