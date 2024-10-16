<?php

namespace App\Http\Requests;

use App\Models\Patient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRendezVousRequest extends FormRequest
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
            'medecin_id' => [
                'required',
                'exists:medecins,id',
            ],
            'patient_id' => [
                'required',
                'exists:patients,id',
            ],
            'date' => ['required', 'date', 'date_format:Y-m-d'], // Format Y-m-d pour la date
            'heure_debut' => ['required', 'date_format:H:i:s'],
            'heure_fin' => ['required', 'date_format:H:i:s', 'after:heure_debut'],
            'type_rendez_vous' => ['required', 'in:présentiel,téléconsultation'],
            'motif' => ['required', 'in:consultation,suivi'],
            'status' => ['required', 'in:à venir,en cours,terminé,annulé'],
            'lieu' => ['nullable', 'string']
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
