<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssistantRequest;
use App\Http\Requests\UpdateAssistantRequest;
use App\Models\Assistant;

class AssistantController extends Controller
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
    public function store(StoreAssistantRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Assistant $Assistant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssistantRequest $request, Assistant $Assistant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assistant $Assistant)
    {
        //
    }
}
