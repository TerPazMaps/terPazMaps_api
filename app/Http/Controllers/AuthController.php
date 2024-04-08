<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    
    public function login(Request $request){

        $credenciais = $request->all();
        
        $token = Auth('api')->attempt($credenciais);
        
        if ($token){
            return response()->json(['Token'=> $token], 200);
        }else{
            return response()->json(['erro'=>'Erro de usuário ou senha'], 403);
        }
    }
    
    public function logout(){
        auth('api')->logout();
        return response()->json(['msg' => 'Logout foi realizado com sucesso']);
    }
    
    public function refresh(){

        $token = auth('api')->refresh();

        return response()->json(['token'=>$token]);
    }
    
    public function me(){
        return response()->json(Auth()->user());
    }

}
