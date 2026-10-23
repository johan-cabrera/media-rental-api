<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Listado de usuarios con paginado y filtros
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = User::query();

        // Filtro por rol
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Filtro por nombre o correo
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Paginacion con 10 elementos por pagina
        $users = $query->latest('id')->paginate(10);

        return UserResource::collection($users);
    }

    // Almacenar un usuario
    public function store(UserStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    // Consultar un usuario
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    // Actualizar un usuario
    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        $validated = $request->validated();

        // Encriptar contraseña o retirarla para no sobreescribirla  
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return new UserResource($user);
    }

    // Eliminar un usuario
    public function destroy(User $user): JsonResponse
    {
        // impedir borrarse a uno mismo
        if (auth()->id() === $user->id) {
            return response()->json([
                'message' => 'No puedes eliminar tu propia cuenta de administrador.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado exitosamente.',
        ], 200);
    }
}