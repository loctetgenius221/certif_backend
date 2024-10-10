<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateConsultationsRequest extends FormRequest
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
            "date" => ["nullable", "date"],
            "heure_debut" => ["nullable", "date_format:H:i"],
            "heure_fin" => ["nullable", "date_format:H:i", "after:heure_debut"],
            "type_consultation" => ["nullable", "string", "max:255"],
            "diagnostic" => ["nullable", "string"],
            "notes_medecin" => ["nullable", "string"],
            "url_teleconsultation" => ["nullable", "string", "max:255"],
        ];
    }
    /**
     * Gérer l'échec de la validation.
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['success' => false, 'errors' => $validator->errors()], 422)
        );
    }

}
