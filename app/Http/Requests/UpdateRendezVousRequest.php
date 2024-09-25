<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRendezVousRequest extends FormRequest
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
                'sometimes', // 'required' est remplacé par 'sometimes' pour indiquer que ce champ est optionnel
                'exists:medecins,id',
                function ($attribute, $value, $fail) {
                    $user = \App\Models\User::find($value);
                    if ($value && (!$user || !$user->hasRole('médecin'))) {
                        $fail('L\'utilisateur sélectionné n\'est pas un médecin.');
                    }
                }
            ],
            'patient_id' => [
                'sometimes', // Ce champ est optionnel pour la mise à jour
                'exists:users,id', // Vérifie si l'ID existe dans la table `users`
                function ($attribute, $value, $fail) {
                    $patient = \App\Models\User::find($value);
                    if ($value && (!$patient || !$patient->hasRole('patient'))) {
                        $fail('L\'utilisateur sélectionné n\'est pas un patient.');
                    }
                }
            ],
            'date' => ['sometimes', 'date', 'date_format:Y-m-d'], // Optionnel et format Y-m-d
            'heure_debut' => ['sometimes', 'date_format:H:i'],
            'heure_fin' => ['sometimes', 'date_format:H:i', 'after:heure_debut'],
            'type_rendez_vous' => ['sometimes', 'in:présentiel,téléconsultation'],
            'motif' => ['sometimes', 'in:consultation,suivi'],
            'status' => ['sometimes', 'in:à venir,en cours,terminé,annulé'],
            'lieu' => ['nullable', 'string'] // Champ optionnel et peut être null
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
