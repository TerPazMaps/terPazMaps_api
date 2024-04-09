<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function indexLogin()
    {
        return view('login');
    }

    public function register(Request $request)
    {
        
        $regras = [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:3'],
        ];

        $feedback = [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser uma string.',
            'name.min' => 'O seu nome deve ter no mínimo 3 letras.',
            'name.max' => 'O seu nome deve ter no máximo 255 letras.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O campo email deve ser um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.string' => 'O campo senha deve ser uma string.',
            'password.min' => 'A senha deve ter no mínimo :min caracteres.',
            'password.confirmed' => 'As senhas não são iguais.',
        ];
        
        $request->validate($regras, $feedback);
        dd($request->all());
    }

    public function login(Request $request)
    {

        $credenciais = $request->all();

        $token = Auth('api')->attempt($credenciais);

        if ($token) {
            return response()->json(['Token' => $token], 200);
        } else {
            return response()->json(['erro' => 'Erro de usuário ou senha'], 403);
        }
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['msg' => 'Logout foi realizado com sucesso']);
    }

    public function refresh()
    {

        $token = auth('api')->refresh();

        return response()->json(['token' => $token]);
    }

    public function me()
    {
        return response()->json(Auth()->user());
    }
}
