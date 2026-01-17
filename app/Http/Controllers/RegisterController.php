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
use Inertia\Response;

class RegisterController extends Controller
{
    public function index(): Response
    {
        if(auth()->user()->role_id === 3){
            $students = StudentResource::collection(Student::where('classroom_id', auth()->user()->classroom_id)
                ->with('registers')
                ->withCount('registers')
                ->where('inactive', false)
                ->orderBy('name')
                ->get());
            $visitants = VisitantResource::collection(Visitant::where('classroom_id', auth()->user()->classroom_id)
                ->whereDate('created_at', today()->toDateString())
                ->get());
        } else {
            $students = StudentResource::collection(Student::query()
                ->when(request()->input('filter'), function ($query, $filter) {
                    $query->where('classroom_id', $filter);
                })
                ->with('registers')
                ->withCount('registers')
                ->where('inactive', false)
                ->whereNotNull('classroom_id')
                ->orderBy('name')
                ->get());
            $visitants = VisitantResource::collection(Visitant::query()
                ->when(request()->input('filter'), function ($query, $filter) {
                    $query->where('classroom_id', $filter);
                })
                ->whereDate('created_at', today()->toDateString())
                ->get());
        }

        return inertia('Register/Index', [
            'students' => $students,
            'classrooms' => ClassroomResource::collection(Classroom::all()->sortBy('name')),
            'selectedClass' => (int) request()->input('filter'),
            'visitants' => $visitants
        ]);
    }

    public function update(Student $student, $sunday): RedirectResponse
    {
        if(!$student->registers()->where('sunday', $sunday)->first()) {
            $student->registers()->create([
                'classroom_id' => $student->classroom_id,
                'sunday' => $sunday,
                'status' => 1
            ]);
        } else {
            $register = Register::where('student_id', $student->id)->where('sunday', $sunday)->first();
            if($register->status === 1){
                $register->update([
                    'status' => 0
                ]);
            } else {
                $register->delete();
            }
        }

        if(auth()->user()->role_id === 3) {
            return to_route('registers.index');
        } else {
            return redirect('/chamada?filter=' . (int) request()->filter);
        }
    }
}
