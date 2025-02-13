<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassroomResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\VisitantResource;
use App\Models\Classroom;
use App\Models\Register;
use App\Models\Student;
use App\Models\Visitant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class RegisterController extends Controller
{
    public function index(): Response
    {
        return inertia('Register/Index', [
            'students' => StudentResource::collection(Student::query()
                ->when(request()->input('filter'), function ($query, $filter) {
                    $query->where('classroom_id', $filter);
                })
                ->with('registers')
                ->orderBy('name')
                ->get()),
            'classrooms' => ClassroomResource::collection(Classroom::all()->sortBy('name')),
            'selectedClass' => (int) request()->input('filter'),
            'visitants' => VisitantResource::collection(Visitant::query()
                ->when(request()->input('filter'), function ($query, $filter) {
                    $query->where('classroom_id', $filter);
                })
                ->whereDate('created_at', today()->toDateString())
                ->get())
        ]);
    }

    public function update(Student $student, $sunday)
    {
        if(!$student->registers()->where('sunday', $sunday)->first()) {
            $student->registers()->create([
                'classroom_id' => $student->classroom_id,
                'sunday' => $sunday,
                'status' => true
            ]);
        } else {
            $register = Register::where('student_id', $student->id)->where('sunday', $sunday)->first();
            $register->update([
                'status' => ! $register->status
            ]);
        }

        return redirect('/chamada?filter=' . (int) request()->filter);
    }
}
