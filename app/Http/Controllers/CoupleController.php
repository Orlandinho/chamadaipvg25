<?php

namespace App\Http\Controllers;

use App\Enums\Bodas;
use App\Http\Resources\CoupleResource;
use App\Models\Couple;
use App\Http\Requests\StoreCoupleRequest;
use App\Http\Requests\UpdateCoupleRequest;
use Inertia\Response;

class CoupleController extends Controller
{
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
        try {
            Couple::create($request->validated());
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
            'couple' => $couple,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCoupleRequest $request, Couple $couple)
    {
        try {
            $couple->update($request->validated());
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
            $couple->delete();
        } catch (\Exception $e) {
            return back()->alertFailure('Não foi possível apagar os dados  do casal');
        }

        return to_route('couples.index')->alertSuccess('Dados do casal apagados!');
    }
}
