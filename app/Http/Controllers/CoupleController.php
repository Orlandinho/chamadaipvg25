<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoupleResource;
use App\Models\Couple;
use App\Http\Requests\StoreCoupleRequest;
use App\Http\Requests\UpdateCoupleRequest;
use Illuminate\Support\Facades\Auth;
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
            if($request->hasFile('husband_avatar')) {
                $data['husband_avatar'] = Storage::disk('avatar')->put('avatars', $request->file('husband_avatar'));
            }
            if($request->hasFile('wife_avatar')) {
                $data['wife_avatar'] = Storage::disk('avatar')->put('avatars', $request->file('wife_avatar'));
            }
            Couple::create($data);
        } catch (\Exception $e) {
            return back()->alertFailure('Não foi possível salvar as informações. Se o problema persistir entre em contato com o suporte.');
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
        $data = $request->validated();
        try {
            if ($request->hasFile('husband_avatar')) {
                if($couple->husband_avatar){
                    Storage::disk('avatar')->delete($couple->husband_avatar);
                }
                $data['husband_avatar'] = Storage::disk('avatar')->put('avatars', $request->husband_avatar);
            } else {
                $data['husband_avatar'] = $couple->husband_avatar;
            }
            if ($request->hasFile('husband_avatar')) {
                if($couple->wife_avatar){
                    Storage::disk('avatar')->delete($couple->wife_avatar);
                }
                $data['wife_avatar'] = Storage::disk('avatar')->put('avatars', $request->wife_avatar);
            } else {
                $data['wife_avatar'] = $couple->wife_avatar;
            }
            $couple->update($data);
        } catch (\Exception $e) {
            return back()->AlertFailure('Não foi possível atualizar as informações do casal');
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
                Storage::disk('avatar')->delete($couple->husband_avatar);
            }
            if($couple->wife_avatar) {
                Storage::disk('avatar')->delete($couple->wife_avatar);
            }
            $couple->delete();
        } catch (\Exception $e) {
            return back()->alertFailure('Não foi possível apagar os dados  do casal');
        }

        return to_route('couples.index')->alertSuccess('Dados do casal apagados!');
    }
}
