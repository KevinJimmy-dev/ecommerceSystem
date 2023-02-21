<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiError;
use App\Api\ApiSuccess;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        try {
            $users = $this->user->with('stores')->paginate(10);

            return response()->json(ApiSuccess::successMessage('Usuários e suas respectivas lojas encontradas!', $users), 200);
        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }

            return response()->json(ApiError::errorMessage('Erro ao listar usuários!', 500), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'document' => 'required|unique:users,document',
                'phone' => 'required|string',
                'password' => 'required|min:4|max:16|confirmed'
            ]);

            $user = $this->user->create([
                'name' => $request->name,
                'email' => $request->email,
                'document' => $request->document,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            return response()->json(ApiSuccess::successMessage('Novo usuário cadastrado com sucesso!', $user), 201);
        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }

            return response()->json(ApiError::errorMessage('Erro ao cadastrar um novo usuário!', 500), 500);
        }
    }

    public function show($id)
    {
        $user = $this->user->find($id)->with('stores')->first();

        if (!$user) {
            return response()->json(ApiError::errorMessage('Nenhum usuário foi encontrado!', 404), 404);
        }

        return response()->json(ApiSuccess::successMessage('Usuário encontrado!', $user), 200);
    }

    public function update(Request $request, $id)
    {
        $user = $this->user->find($id);

        if (!$user) {
            return response()->json(ApiError::errorMessage('Nenhum usuário foi encontrado!', 404), 404);
        }

        try {
            $user->update($request->all());

            return response()->json(ApiSuccess::successMessage('Usuário atualizado com sucesso!', $user), 201);
        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1011), 500);
            }

            return response()->json(ApiError::errorMessage('Erro ao atualizar o usuário!', 1011), 500);
        }
    }

    public function destroy($id)
    {
        $user = $this->user->find($id);

        if (!$user) {
            return response()->json(ApiError::errorMessage('Nenhum usuário foi encontrado!', 404), 404);
        }

        try {
            $user->delete();

            return response()->json(ApiSuccess::successMessage('Usuário, e suas lojas e produtos, foram excluidos com sucesso!', true), 200);
        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012), 500);
            }

            return response()->json(ApiError::errorMessage('Erro ao deletar o usuário!', 1012), 500);
        }
    }
}
