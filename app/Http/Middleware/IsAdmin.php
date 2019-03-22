<?php

namespace App\Http\Middleware;
use JWTAuth;
use Exception;
use Closure;

class IsAdmin
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
      
       if($request->user->isAdmin){
           return $next($request);
       }
       return response()->json(['message'=>'You dont have appropiate rights'],403);
        
    }

    
}
