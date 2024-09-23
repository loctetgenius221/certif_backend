<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();
        return $this->customJsonResponse("Liste des services", $services);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        $service = new Service();
        $service->nom = $request->nom;
        $service->description = $request->description;

        $service->save();

        return response()->json([
            "message" => "Service créé avec succès",
            "data" => $service
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return $this->customJsonResponse("Service récupéré avec succès", $service);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->fill($request->validated());
        $service->update();
        return response()->json([
            "message" => "Service mis à jour avec succès",
            "data" => $service
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return $this->customJsonResponse("Service supprimé avec succès", null, 200);
    }
}
