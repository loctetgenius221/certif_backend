<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Medecin;
use App\Http\Requests\StoreMedecinRequest;
use App\Http\Requests\UpdateMedecinRequest;

class MedecinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedecinRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedecinRequest $request, Medecin $Medecin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medecin $Medecin)
    {
        //
    }
}
