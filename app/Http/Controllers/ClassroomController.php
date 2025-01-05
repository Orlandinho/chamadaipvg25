<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;
use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return inertia("Classroom/Index", [
            "classrooms" => ClassroomResource::collection(Classroom::withCount('students')->orderBy('name')->get()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return inertia("Classroom/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassroomRequest $request): RedirectResponse
    {
        try {
            Classroom::create($request->validated());
        } catch (\Exception $e) {
            return back()->alertFailure('Não foi possível criar a classe. Se o problema persistir contate o suporte.');
        }

        return to_route('classrooms.index')->alertSuccess('Classe criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom): Response
    {
        return inertia("Classroom/Show", [
            "classroom" => new ClassroomResource($classroom->loadCount('students')),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom): Response
    {
        return inertia("Classroom/Edit", [
            "classroom" => new ClassroomResource($classroom),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassroomRequest $request, Classroom $classroom): RedirectResponse
    {
        try {
            $classroom->update($request->validated());
        } catch (\Exception $e) {
            return back()->alertFailure("Não foi possível atualizar os dados da classe {$classroom->name}. Se o problema persistir contate o suporte.");
        }

        return to_route('classrooms.index')->alertSuccess("Classe dos {$classroom->name} atualizada com sucesso!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom): RedirectResponse
    {
        try {
            $classroom->delete();
        } catch (\Exception $e) {
            return back()->alertFailure("Não foi possível deletar os dados da classe {$classroom->name}. Se o problema persistir contate o suporte.");
        }

        return to_route('classrooms.index')->alertSuccess("Classe deletada com sucesso!");
    }
}
