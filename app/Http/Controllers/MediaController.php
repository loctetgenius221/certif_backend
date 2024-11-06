<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator; // Assurez-vous d'importer cette façade

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medias = Media::all();
        return $this->customJsonResponse("Liste des médias", $medias);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $validator = Validator::make($request->all(), [
            'media' => 'required|file|mimes:jpg,jpeg,png,gif,mp4|max:2048',
            'nom' => 'required|string|max:255', // Validation pour le nom
        ]);

        // Vérifiez si la validation a échoué
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('media');
        $path = $file->store('media', 'public');

        try {
            // Utilisez le nom renseigné dans le formulaire
            $media = Media::create([
                'nom' => $request->input('nom'), // Prendre le nom du champ "nom" dans le formulaire
                'url' => asset('storage/' . $path), // Enregistrer l'URL complète dans le champ `url`
            ]);

            return response()->json([
                "message" => "Média ajouté avec succès",
                "data" => $media
            ], 201);
        } catch (\Exception $e) {
            // Capturez l'exception et retournez l'erreur
            return response()->json([
                "message" => "Erreur lors de l'ajout du média",
                "error" => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $media = Media::findOrFail($id);
        return $this->customJsonResponse("Service récupéré avec succès", $media);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Recherche du média
        $media = Media::find($id);
        if (!$media) {
            return response()->json([
                'message' => 'Média non trouvé'
            ], 404);
        }

        // Validation des données du formulaire
        $validationRules = [
            'nom' => 'sometimes|required|string|max:255'
        ];

        // Ajouter la validation du média seulement s'il est fourni
        if ($request->hasFile('media')) {
            $validationRules['media'] = 'required|file|mimes:jpg,jpeg,png,gif,mp4|max:2048';
        }

        $validator = Validator::make($request->all(), $validationRules);

        // Vérifiez si la validation a échoué
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Mise à jour du nom si fourni et non vide
            if ($request->filled('nom')) {  // Changement ici : has() -> filled()
                $media->nom = $request->input('nom');
            }

            // Traitement du nouveau fichier média si fourni
            if ($request->hasFile('media')) {
                // Extraire le chemin du fichier de l'URL
                $oldPath = str_replace(asset('storage/'), '', parse_url($media->url, PHP_URL_PATH));
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }

                // Stocker le nouveau fichier
                $file = $request->file('media');
                $path = $file->store('media', 'public');
                $media->url = asset('storage/' . $path);
            }

            // Forcer la mise à jour du timestamp
            $media->touch();
            $media->save();

            return response()->json([
                "message" => "Média mis à jour avec succès",
                "data" => $media->fresh()  // Récupérer les données fraîches depuis la base
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "message" => "Erreur lors de la mise à jour du média",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Chercher le média par ID
        $media = Media::find($id);

        // Vérifiez si le média existe
        if (!$media) {
            return response()->json([
                'message' => 'Média non trouvé'
            ], 404); // Code d'erreur 404 si le média n'est pas trouvé
        }

        try {
            // Extraire le chemin du fichier à partir de l'URL (en supposant que l'URL contient 'storage/')
            $path = str_replace(asset('storage/'), '', $media->url);

            // Supprimer le fichier du stockage
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            // Supprimer l'entrée du média dans la base de données
            $media->delete();

            return response()->json([
                'message' => 'Média supprimé avec succès'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression du média',
                'error' => $e->getMessage()
            ], 500); // Code d'erreur 500 pour les erreurs serveur
        }
    }

}
