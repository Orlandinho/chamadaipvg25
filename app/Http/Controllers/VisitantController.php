<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassroomResource;
use App\Http\Resources\VisitantResource;
use App\Models\Classroom;
use App\Models\Visitant;
use App\Http\Requests\StoreVisitantRequest;
use App\Http\Requests\UpdateVisitantRequest;
use Inertia\Response;

class VisitantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return inertia('Visitant/Index', [
            'visitants' => VisitantResource::collection(Visitant::with('classroom')->latest()->paginate(15))
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return inertia('Visitant/Create', [
            'classrooms' => ClassroomResource::collection(Classroom::all()->sortBy('name'))
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVisitantRequest $request)
    {
        try {
            Visitant::create($request->validated());
        } catch (\Exception $e) {
            return back()->alertFailure('Não foi possível registrar o visitante.');
        }

        return to_route('visitants.index')->alertSuccess('Visitante registrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Visitant $visitant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visitant $visitant): Response
    {
        return inertia('Visitant/Edit', [
            'visitant' => new VisitantResource($visitant),
            'classrooms' => ClassroomResource::collection(Classroom::all()->sortBy('name'))
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVisitantRequest $request, Visitant $visitant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visitant $visitant)
    {
        //
    }
}
