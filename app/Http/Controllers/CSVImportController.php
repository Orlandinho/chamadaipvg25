<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Models\Couple;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CSVImportController extends Controller
{
    public function __construct()
    {
        if (Auth::user()->role_id !== Roles::ADMIN->value) {
            abort(403);
        }
    }
    public function importStudents(Request $request)
    {
        $request->validate([
            'students_csv' => ['required','file','mimes:csv','max:2048']
        ]);

        $file = $request->file('students_csv');

        $handle = fopen($file->getPathname(), 'r');

        $header = fgetcsv($handle);

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                Student::create([
                    'name' => $row[0],
                    'slug' => $this->makeSlugForStudent($row[0]),
                    'dob' => Carbon::createFromFormat('d/m/Y', $row[1])->format('Y-m-d'),
                    'contact' => $row[2],
                    'inactive' => (bool) $row[3],
                ]);
            }

            fclose($handle);

            DB::commit();

            return redirect()->back()->alertSuccess('Dados dos alunos importados!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->alertFailure('Não foi possível importar os dados dos alunos');
        }
    }

    public function importCouples(Request $request)
    {
        $request->validate([
            'couples_csv' => ['required','file','mimes:csv','max:2048']
        ]);

        $file = $request->file('couples_csv');

        $handle = fopen($file->getPathname(), 'r');

        $header = fgetcsv($handle);

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                Couple::create([
                    'husband' => $row[0],
                    'wife' => $row[1],
                    'slug' => $this->makeSlugForCouple($row[0], $row[1]),
                    'marriage_date' => Carbon::createFromFormat('d/m/Y', $row[2])->format('Y-m-d'),
                ]);
            }

            fclose($handle);

            DB::commit();

            return redirect()->back()->alertSuccess('Dados dos casais importados!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->alertFailure('Não foi possível importar os dados dos casais');
        }
    }

    protected function makeSlugForStudent(string $name): string
    {
        $slug = Str::slug($name);

        $count = Student::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    protected function makeSlugForCouple(string $husband, string $wife): string
    {

        $husband = explode(' ', $husband);
        $wife = explode(' ', $wife);
        $slug = Str::slug($husband[0] . ' e ' . $wife[0], '_');

        $count = Couple::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
