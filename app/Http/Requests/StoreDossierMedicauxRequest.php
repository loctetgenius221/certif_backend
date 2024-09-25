<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDossierMedicauxRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole('médecin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'], // Assure que l'utilisateur existe
            'date_creation' => ['required', 'date'],
            'antecedents_medicaux' => ['nullable', 'string'],
            'traitements' => ['nullable', 'string'],
            'notes_observations' => ['nullable', 'string'],
            'intervention_chirurgicale' => ['nullable', 'string'],
            'info_sup' => ['nullable', 'string'],
        ];
    }
}
