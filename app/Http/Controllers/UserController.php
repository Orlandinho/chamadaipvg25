<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Http\Resources\ClassroomResource;
use App\Http\Resources\UserResource;
use App\Models\Classroom;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct()
    {
        if (Auth::user()->role_id !== Roles::ADMIN->value) {
            abort(403);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return inertia('User/Index', [
            'users' => UserResource::collection(User::all()->sortBy('name'))
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return inertia('User/Create', [
            'roles' => Roles::allRoles(),
            'classrooms' => ClassroomResource::collection(Classroom::all()->sortBy('name'))
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        try {
            if($request->hasFile('avatar')) {
                $data['avatar'] = Storage::disk('avatar')->put('avatars', $request->file('avatar'));
            }
            User::create($data);
        } catch (\Exception $e) {
            return back()->alertFailure('Não foi possível realizar o cadastro. Se o problema persistir entre em contato com o suporte.');
        }

        return to_route('users.index')->alertSuccess('Cadastro realizado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): Response
    {
        return inertia('User/Show', [
            'user' => new UserResource($user->load('classroom'))
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Response
    {
        return inertia('User/Edit', [
            'user' => new UserResource($user->load('classroom')),
            'roles' => Roles::allRoles(),
            'classrooms' => ClassroomResource::collection(Classroom::all()->sortBy('name'))
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        try {
            if ($request->hasFile('avatar')) {
                if($user->avatar){
                    Storage::disk('avatar')->delete($user->avatar);
                }
                $data['avatar'] = Storage::disk('avatar')->put('avatars', $request->avatar);
            } else {
                $data['avatar'] = $user->avatar;
            }
            $user->update($data);
        } catch (\Exception $e) {
            return back()->alertFailure('Não foi possível realizar a atualização dos dados. Se o problema persistir entre em contato com o suporte.');
        }

        return to_route('users.index')->alertSuccess('Atualização realizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            if($user->avatar) {
                Storage::disk('avatar')->delete($user->avatar);
            }
            $user->delete();
        } catch (\Exception $e) {
            return back()->alertFailure("Não foi possível excluir os dados do colaborador(a) {$user->name}. Se o problema persistir entre em contato com o suporte.");
        }

        return to_route('users.index')->alertSuccess('Dados excluídos com sucesso!');
    }
}
