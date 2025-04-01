<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassroomResource;
use App\Http\Resources\CoupleResource;
use App\Http\Resources\StudentResource;
use App\Models\Classroom;
use App\Models\Couple;
use App\Models\Register;
use App\Models\Student;
use App\Models\Visitant;
use Carbon\Carbon;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        /*dd(Register::select('classroom_id')
            ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as true_count')
            ->selectRaw('SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as false_count')
            ->groupBy('classroom_id')
            ->get());*/

        /*$results = DB::table('your_table_name')
            ->select(
                'classroom_id',
                DB::raw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as true_count'),
                DB::raw('SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as false_count')
            )
            ->whereBetween('created_at', [$startDate, $endDate]) // Assuming you have a 'created_at' column
            ->groupBy('classroom_id')
            ->get();*/


        $nextSunday = now()->isSunday() ? now() : Carbon::parse('next sunday');
        $previousMonday = now()->isMonday() ? now() : Carbon::parse('previous monday');

        $absentStudents = Register::select('student_id')
            ->where('sunday', '>=', now()->subWeeks(4)->toDateString())
            ->groupBy('student_id')
            ->havingRaw('SUM(status) = 0')
            ->havingRaw('COUNT(*) > 3')
            ->pluck('student_id');

        return inertia('Dashboard', [
            'birthdays' => StudentResource::collection(
                Student::whereRaw("DATE_FORMAT(dob, '%m-%d') BETWEEN ? AND ?",
                    [
                        $previousMonday->format('m-d'), $nextSunday->format('m-d')
                    ])
                ->orderByRaw("DATE_FORMAT(dob, '%m-%d')")
                ->get()),
            'marriage_birthdays' => CoupleResource::collection(
                Couple::whereRaw("DATE_FORMAT(marriage_date, '%m-%d') BETWEEN ? AND ?",
                    [
                        $previousMonday->format('m-d'), $nextSunday->format('m-d')
                    ])
                ->orderByRaw("DATE_FORMAT(marriage_date, '%m-%d')")
                ->get()),
            'sunday' => [
                'previous' =>  $previousMonday->format('d/m'),
                'next' => $nextSunday->format('d/m')
            ],
            'stats' => [
                'total_students' => Student::where('inactive', false)->count(),
                'frequency' => Register::all()->count() < 1 ? 'Não definido' : round((Register::where('status', true)->count() / Register::all()->count()) * 100, 2) . '%',
                'total_visits' => Visitant::all()->count(),
                'total_unique_visits' => Visitant::select('name')->distinct('name')->count(),
                'absent_students' => StudentResource::collection(Student::with('classroom')->find($absentStudents)),
                'classrooms_stats' => ClassroomResource::collection(Classroom::withCount(['active_students','visitants'])->get()),
            ]
        ]);
    }
}
