<?php

namespace App\Http\Controllers;

use Exception;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\PasswordUpdate;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{

    public function indexLogin()
    {
        return view('login');
    }

    public function viewSendPasswordResetNotification()
    {
        return view('sendemail');
    }

    public function register(Request $request)
    {
        $regras = [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ];

        $feedback = [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser uma string.',
            'name.min' => 'O seu nome deve ter no mínimo :min letras.',
            'name.max' => 'O seu nome deve ter no máximo :max letras.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O campo email deve ser um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.string' => 'O campo senha deve ser uma string.',
            'password.min' => 'A senha deve ter no mínimo :min caracteres.',
            'password.confirmed' => 'As senhas não são iguais.',
        ];

        $validator = Validator($request->all(), $regras, $feedback);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Criar o usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Envie uma resposta JSON com os detalhes do usuário registrado
        return response()->json(['message' => 'Usuário registrado com sucesso', 'user' => $user], 201);
    }

    public function sendPasswordResetNotification(Request $request)
    {
        $regras = [
            'email' => ['required', 'email', 'exists:users'],
        ];

        $feedback = [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O campo email deve ser um endereço de e-mail válido.',
            'email.exists' => 'Este e-mail não pertence a um usuário.',
        ];

        $validator = Validator($request->all(), $regras, $feedback);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $token = Str::random(60);
        User::where('email', $request->email)->update(['password_reset' =>  $token]);

        $user = User::where('email', $request->email)->firstOrFail();

        try {
            Mail::to($request->email)->send(new PasswordUpdate($user, $token));
            return response()->json(['message' => 'O email foi enviado com sucesso.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Não foi possível enviar a mensagem.', 'exception' => $e], 500);
        }
    }

    public function viewResetPassword(Request $request)
    {
        $token = $request->token;
        $email = $request->email;

        return view('viewresetpassword', compact(['token', 'email']));
    }

    public function resetPassword(Request $request)
    {
        $regras = [
            'password' => ['required', 'min:8', 'confirmed'],
            'password_reset' => ['required', 'exists:users'],
        ];

        $feedback = [
            'password.required' => 'O campo senha é obrigatório.',
            'password.string' => 'O campo senha deve ser uma string.',
            'password.min' => 'A senha deve ter no mínimo :min caracteres.',
            'password.confirmed' => 'As senhas não são iguais.',
            'password_reset.exists' => 'O token de recuperação de senha não é válido.',
            'password_reset.required' => 'O token de recuperação não está presente.',
        ];

        $validator = Validator::make($request->all(), $regras, $feedback);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $newPassword = User::where('email', $request->email)
                ->update([
                    'password' =>  Hash::make($request->password),
                    'password_reset' => null,
                ]);

            if ($newPassword) {
                return redirect()->back()->with('success', 'Senha atualizada com sucesso!');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('danger', 'Não foi possível alterar sua senha. O token de solicitação foi expirado ou o usuário não existe.');
        }
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
