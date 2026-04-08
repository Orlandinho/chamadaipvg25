<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Models\Classroom;
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

    public function index()
    {
        return inertia('Import/Index');
    }
    public function importStudents(Request $request)
    {
        $request->validate([
            'students_csv' => ['required', 'file', 'mimes:csv,txt', 'max:2048']
        ]);

        $file = $request->file('students_csv');
        $handle = fopen($file->getPathname(), 'r');
        fgetcsv($handle); // Pula o cabeçalho

        $batchSize = 500;
        $studentsBatch = [];
        $now = now(); // Para os timestamps created_at/updated_at

        DB::beginTransaction();

        try {
            $classroomMap = [];
            while (($row = fgetcsv($handle)) !== false) {

                $classroomName = trim($row[1]);

                // Se a sala ainda não foi consultada neste loop, busca ou cria
                if (!isset($classroomMap[$classroomName])) {
                    $classroom = Classroom::firstOrCreate(['name' => $this->formatTitleWithExceptions($classroomName), 'slug' => Str::slug($classroomName)]);
                    $classroomMap[$classroomName] = $classroom->id;
                }

                $classroomId = $classroomMap[$classroomName];

                $studentsBatch[] = [
                    'name'         => $this->formatTitleWithExceptions($row[0]),
                    'slug'         => $this->makeSlugForStudent($row[0]),
                    'classroom_id' => $classroomId,
                    'dob'          => Carbon::createFromFormat('d/m/Y', $row[2])->format('Y-m-d'),
                    'contact'      => $row[3],
                    'inactive'     => false,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];

                // Quando atingir o tamanho do lote, insere e limpa o array
                if (count($studentsBatch) >= $batchSize) {
                    Student::insert($studentsBatch);
                    $studentsBatch = [];
                }
            }

            // Insere o restante que sobrou no array
            if (!empty($studentsBatch)) {
                Student::insert($studentsBatch);
            }

            fclose($handle);
            DB::commit();

            return redirect()->back()->with('success', 'Dados dos alunos importados!');
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', 'Erro na importação: ' . $e->getMessage());
        }
    }

    public function importCouples(Request $request)
    {
        $request->validate([
            'couples_csv' => ['required', 'file', 'mimes:csv,txt', 'max:2048']
        ]);

        $file = $request->file('couples_csv');
        $handle = fopen($file->getPathname(), 'r');
        fgetcsv($handle); // Pula o cabeçalho

        $batchSize = 500;
        $studentsBatch = [];
        $now = now(); // Para os timestamps created_at/updated_at

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $studentsBatch[] = [
                    'husband'         => $this->formatTitleWithExceptions($row[0]),
                    'wife'         => $this->formatTitleWithExceptions($row[1]),
                    'slug'         => $this->makeSlugForCouple($row[0], $row[1]),
                    'classroom_id' => $row[1],
                    'marriage_date' => Carbon::createFromFormat('d/m/Y', $row[2])->format('Y-m-d'),
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];

                // Quando atingir o tamanho do lote, insere e limpa o array
                if (count($studentsBatch) >= $batchSize) {
                    Student::insert($studentsBatch);
                    $studentsBatch = [];
                }
            }

            // Insere o restante que sobrou no array
            if (!empty($studentsBatch)) {
                Student::insert($studentsBatch);
            }

            fclose($handle);
            DB::commit();

            return redirect()->back()->with('success', 'Dados dos alunos importados!');
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', 'Erro na importação: ' . $e->getMessage());
        }
    }

    protected function makeSlugForStudent(string $name): string
    {
        $originalSlug = Str::slug($name);
        $slug = $originalSlug;
        $count = 1;

        while (Student::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    protected function makeSlugForCouple(string $husband, string $wife): string
    {
        $husband = explode(' ', $husband);
        $wife = explode(' ', $wife);
        $originalSlug = Str::slug($husband[0] . ' e ' . $wife[0], '_');
        $slug = $originalSlug;
        $count = 1;

        while (Couple::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $count ? "{$slug}-{$count}" : $slug;
    }

    protected function formatTitleWithExceptions($string)
    {
        $exceptions = ['de', 'di', 'do', 'da', 'dos', 'das', 'e', 'em'];

        // 1. Transforma tudo em Title Case (Antonio De Oliveira)
        $title = Str::title($string);

        // 2. Procura as exceções seguidas de espaço e as corrige
        foreach ($exceptions as $exception) {
            // O padrão busca a exceção com a primeira letra maiúscula cercada por espaços
            $pattern = '/\b' . ucfirst($exception) . '\b/u';
            $title = preg_replace($pattern, $exception, $title);
        }

        return $title;
    }
}
