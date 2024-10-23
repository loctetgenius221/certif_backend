<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Documents;
use App\Models\DossierMedicaux;
use App\Http\Requests\StoreDocumentsRequest;
use App\Http\Requests\UpdateDocumentsRequest;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Documents::all();
        return $this->customJsonResponse("Liste des documents récupérée avec succès", $documents);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentsRequest $request)
    {
        Log::info($request->all());
        // Récupérez l'utilisateur connecté
        $user = auth()->user();

        // Vérifiez si le dossier médical existe
        $dossier = DossierMedicaux::find($request->dossier_medical_id);
        if (!$dossier) {
            return response()->json([
                "message" => "Dossier médical non trouvé."
            ], 404);
        }

        $document = new Documents();
        $document->dossier_medical_id = $request->dossier_medical_id;
        $document->type_document = $request->type_document;
        $document->upload_date = now();
        $document->upload_by = $user->id;

        // Gestion de l'upload du fichier
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $document->file_path = $file->store('documents', 'public');
        }

        $document->save();

        return response()->json([
            "message" => "Document créé avec succès.",
            "data" => $document
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Documents $documents)
    {
        return $this->customJsonResponse("Document récupéré avec succès", $documents);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentsRequest $request, Documents $documents)
    {
        // Vérifiez si un nouveau fichier est téléchargé
        if ($request->hasFile('file')) {
            // Supprimez l'ancien fichier
            Storage::disk('public')->delete($document->file_path);

            // Stockez le nouveau fichier
            $file = $request->file('file');
            $document->file_path = $file->store('documents', 'public');
        }

        $document->type_document = $request->type_document ?? $document->type_document;
        $document->save();

        return response()->json([
            "message" => "Document mis à jour avec succès.",
            "data" => $document
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Documents $documents)
    {
        // Supprimez le fichier
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return $this->customJsonResponse("Document supprimé avec succès.", null, 200);
    }
}
