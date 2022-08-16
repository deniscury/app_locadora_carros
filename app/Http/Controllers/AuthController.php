<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->all(['email', 'password']);

        // Autenticação Email e Senha
        $token = auth('api')->attempt($credentials);
        
        if (!$token){
            return response()->json(array(
                'erro' => 'Usuário ou senha inválidos'
            ), 403);

            // 401 -> Unauthorized -> não autorizado
            // 403 - Forbidden -> proibido (login inválido)
        }

        // Retornar Json Web Token
        return response()->json(array(
            'token' => $token
        ));
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(array(
            'msg' => 'Logout realizado com sucesso!'
        ));
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = auth('api')->refresh(); //Client precisa encaminhar um JWT válido 

        return response()->json(array(
            'token' => $token
        ));
    }
}