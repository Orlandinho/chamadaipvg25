<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoupleResource;
use App\Models\Couple;
use App\Http\Requests\StoreCoupleRequest;
use App\Http\Requests\UpdateCoupleRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;

class CoupleController extends Controller
{
    public function __construct()
    {
        if (Auth::user()->role_id > 2) {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return inertia('Couple/Index', [
            'couples' => CoupleResource::collection(Couple::paginate(15)),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return inertia('Couple/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCoupleRequest $request)
    {
        $data = $request->validated();

        try {
            if ($file = $request->file('husband_avatar')) {

                $path = $file->store(image_path(), 's3');

                if (! $path) {
                    throw new \RuntimeException('Upload failed');
                }

                $data['husband_avatar'] = $path;
            }

            if ($file = $request->file('wife_avatar')) {

                $path = $file->store(image_path(), 's3');

                if (! $path) {
                    throw new \RuntimeException('Upload failed');
                }

                $data['wife_avatar'] = $path;
            }

            Couple::create($data);
        } catch (\Throwable $e) {
            return back()->alertFailure('Não foi possível realizar o cadastro. Se o problema persistir entre em contato com o suporte.');
        }

        return to_route('couples.index')->alertSuccess('Casal registrado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Couple $couple): Response
    {
        return inertia('Couple/Edit', [
            'couple' => CoupleResource::make($couple),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCoupleRequest $request, Couple $couple)
    {
        $data = $request->safe()->except('husband_avatar', 'wife_avatar');
        $oldHusbandPath = $couple->husband_avatar;
        $oldWifePath = $couple->wife_avatar;
        $newHusbandPath = null;
        $newWifePath = null;

        DB::beginTransaction();

        try {
            if ($request->hasFile('husband_avatar')) {

                $newHusbandPath = $request->file('husband_avatar')->store(image_path(), 's3');

                if (! $newHusbandPath) {
                    throw new \RuntimeException('Upload failed');
                }

                $data['husband_avatar'] = $newHusbandPath;
            }

            if ($request->hasFile('wife_avatar')) {

                $newWifePath = $request->file('wife_avatar')->store(image_path(), 's3');

                if (! $newWifePath) {
                    throw new \RuntimeException('Upload failed');
                }

                $data['wife_avatar'] = $newWifePath;
            }

            $couple->update($data);

            DB::commit();

            if ($newHusbandPath && $oldHusbandPath) {
                Storage::disk('s3')->delete($oldHusbandPath);
            }

            if ($newWifePath && $oldWifePath) {
                Storage::disk('s3')->delete($oldWifePath);
            }

        } catch (\Throwable $e) {
            DB::rollBack();

            if ($newHusbandPath) {
                Storage::disk('s3')->delete($newHusbandPath);
            }

            if ($newWifePath) {
                Storage::disk('s3')->delete($newWifePath);
            }

            report($e);

            return back()->alertFailure('Erro ao atualizar dados do casal.');
        }

        return to_route('couples.index')->alertSuccess('Informações do casal atualizadas!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Couple $couple)
    {
        try {
            if($couple->husband_avatar) {
                Storage::disk('s3')->delete($couple->husband_avatar);
            }
            if($couple->wife_avatar) {
                Storage::disk('s3')->delete($couple->wife_avatar);
            }
            $couple->delete();
        } catch (\Exception $e) {
            return back()->alertFailure('Não foi possível apagar os dados  do casal');
        }

        return to_route('couples.index')->alertSuccess('Dados do casal apagados!');
    }
}
