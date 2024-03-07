<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

     
    // Essa função em middleware faz com que verifique se o usuario esta logado e coloque isso nas routes para verificar na propria rota
    // Tem que registrar o middleware no arquivo Kernel.php assim: 
    // 'auth.check' => \App\Http\Middleware\CheckAuth::class,

    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check()){
            Auth::logout();

            return redirect()->route('login');
        }

        return $next($request);
    }
}
