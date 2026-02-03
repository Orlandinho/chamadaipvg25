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
use Illuminate\Support\Facades\Storage;
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
            $data = $request->validated();
            if($request->hasFile('avatar')){
                $data['avatar'] = Storage::disk('avatar')->put('avatars', $request->avatar);
            }
            Student::create($data);
        } catch (\Exception $e) {
            return back()->alertFailure('Não foi possível realizar o cadastro. Se o problema persistir entre em contato com o suporte');
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

        $data = $request->validated();

        try {
            if ($request->hasFile('avatar')) {
                if($student->avatar){
                    Storage::disk('avatar')->delete($student->avatar);
                }
                $data['avatar'] = Storage::disk('avatar')->put('avatars', $request->avatar);
            } else {
                $data['avatar'] = $student->avatar;
            }
            $student->update($data);
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
            if($student->avatar) {
                Storage::disk('avatar')->delete($student->avatar);
            }
            $student->delete();
        } catch (\Exception $e) {
            return back()->alertFailure("Falha ao excluir as informações do(a) aluno(a) {$student->name}.");
        }

        return to_route('students.index')->alertSuccess("Aluno(a) removido!");
    }
}
