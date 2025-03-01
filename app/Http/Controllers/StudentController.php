<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Http\Resources\ClassroomResource;
use App\Http\Resources\StudentResource;
use App\Models\Classroom;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return inertia('Student/Index', [
            'students' => StudentResource::collection(Student::query()
                ->when(auth()->user()->role_id === Roles::PROFESSOR->value, function ($query) {
                    $query->where('classroom_id', auth()->user()->classroom_id);
                })
                ->with(['classroom','registers'])
                ->orderBy('name')
                ->paginate(15)),
            'classrooms' => ClassroomResource::collection(Classroom::all()->sortBy('name')),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return inertia('Student/Create', [
            'classrooms' => ClassroomResource::collection(Classroom::query()
                ->when(auth()->user()->role_id === Roles::PROFESSOR->value, function ($query) {
                    $query->where('id', auth()->user()->classroom_id);
                })
                ->orderBy('name')
                ->get()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        try {
            Student::create($request->validated());
        } catch (\Exception $e) {
            return back()->alertFailure('Não foi possível realizar o cadastro. Se o problema persistir entre em contato com o suporte.');
        }

        return to_route('students.index')->alertSuccess('Cadastro realizado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): Response
    {
        if (auth()->user()->role_id === Roles::PROFESSOR->value) {
            if (auth()->user()->classroom_id !== $student->classroom_id) {
                abort(403);
            }
        }

        return inertia('Student/Show', [
            'student' => StudentResource::make($student->load(['classroom','registers'])),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student): Response
    {
        if (auth()->user()->role_id === Roles::PROFESSOR->value) {
            if (auth()->user()->classroom_id !== $student->classroom_id) {
                abort(403);
            }
        }

        return inertia('Student/Edit', [
            'student' => StudentResource::make($student->load('classroom')),
            'classrooms' => ClassroomResource::collection(Classroom::all()->sortBy('name')),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        if (auth()->user()->role_id === Roles::PROFESSOR->value) {
            if (auth()->user()->classroom_id !== $student->classroom_id) {
                abort(403);
            }
        }

        try {
            $student->update($request->validated());
        } catch (\Exception $e) {
            return back()->alertFailure("Não foi possível atualizar as informações do(a) aluno(a) {$student->name}. Se o problema persistir entre em contato com o suporte.");
        }

        return to_route('students.index')->alertSuccess("Informações do aluno(a) {$student->name} atualizadas!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        if (auth()->user()->role_id === Roles::PROFESSOR->value) {
            if (auth()->user()->classroom_id !== $student->classroom_id) {
                abort(403);
            }
        }

        try {
            $student->delete();
        } catch (\Exception $e) {
            return back()->alertFailure("Falha ao excluir as informações do(a) aluno(a) {$student->name}.");
        }

        return to_route('students.index')->alertSuccess("Aluno(a) removido!");
    }
}
