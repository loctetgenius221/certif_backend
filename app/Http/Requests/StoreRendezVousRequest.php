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
                function ($attribute, $value, $fail) {
                    $user = \App\Models\User::find($value);
                    if (!$user || !$user->hasRole('médecin')) {
                        $fail('L\'utilisateur sélectionné n\'est pas un médecin.');
                    }
                }
            ],
            'patient_id' => [
                'required',
                'exists:users,id', // Vérifie si l'ID existe dans la table `users`
                function ($attribute, $value, $fail) {
                    // Trouver l'utilisateur dans la table `users`
                    $patient = \App\Models\User::find($value);

                    // Vérifier si cet utilisateur a le rôle `patient`
                    if (!$patient || !$patient->hasRole('patient')) {
                        $fail('L\'utilisateur sélectionné n\'est pas un patient.');
                    }
                }
            ],
            'date' => ['required', 'date', 'date_format:Y-m-d'], // Format Y-m-d pour la date
            'heure_debut' => ['required', 'date_format:H:i'],
            'heure_fin' => ['required', 'date_format:H:i', 'after:heure_debut'],
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
