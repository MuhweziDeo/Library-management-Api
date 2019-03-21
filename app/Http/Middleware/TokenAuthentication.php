<?php

namespace App\Http\Middleware;
use JWTAuth;
use Exception;
use Closure;

class TokenAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->header('Authorization')){
            try {
                $user = JWTAuth::parseToken($request->header('Authorization'))->authenticate();
                return $next(['request'=>$request,
                'user'=>$user]);
               

            } catch (Exception $e) {
                return response()->json(['message'=>'Token Is Expired or Invalid'],400);
                
            }
          }
          return response()->json([
            'message' => 'Missing Authorization',
          ],401);
    }
}
