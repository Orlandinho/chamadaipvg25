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
use Illuminate\Support\Facades\DB;
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
        $data = $request->validated();

        try {
            if ($file = $request->file('avatar')) {

                $path = $file->store(image_path(), 's3');

                if (! $path) {
                    throw new \RuntimeException('Upload failed');
                }

                $data['avatar'] = $path;
                Student::create($data);
            }
        } catch (\Throwable $e) {
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



        $data = $request->safe()->except('avatar');
        $oldPath = $student->avatar;
        $newPath = null;

        DB::beginTransaction();

        try {
            if ($request->hasFile('avatar')) {

                $newPath = $request->file('avatar')->store(image_path(), 's3');

                if (! $newPath) {
                    throw new \RuntimeException('Upload failed');
                }

                $data['avatar'] = $newPath;
            }

            $student->update($data);

            DB::commit();

            if ($newPath && $oldPath) {
                Storage::disk('s3')->delete($oldPath);
            }

        } catch (\Throwable $e) {
            DB::rollBack();

            if ($newPath) {
                Storage::disk('s3')->delete($newPath);
            }

            report($e);

            return back()->alertFailure('Erro ao atualizar usuário.');
        }

        return to_route('students.index')->alertSuccess("Dados do aluno(a) {$student->name} atualizado com sucesso!");
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
                Storage::disk('s3')->delete($student->avatar);
            }
            $student->delete();
        } catch (\Exception $e) {
            return back()->alertFailure("Não foi possível excluir os dados do colaborador(a) {$student->name}. Se o problema persistir entre em contato com o suporte." . $e->getMessage());
        }

        return back()->alertSuccess('Dados excluídos com sucesso!');
    }
}
