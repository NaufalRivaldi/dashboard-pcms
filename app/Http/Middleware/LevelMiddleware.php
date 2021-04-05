<?php
// ------------------------------------------------------------------------------------
namespace App\Http\Middleware;
// ------------------------------------------------------------------------------------
use Closure;
// ------------------------------------------------------------------------------------
use Auth;
// ------------------------------------------------------------------------------------
class LevelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // --------------------------------------------------------------------------------
    public function handle($request, Closure $next, ...$levelArray)
    {
        if(in_array(Auth::user()->level_id, $levelArray)) return $next($request);
        else return redirect('/dashboard')->with('warning', 'Anda tidak dapat mengakses halaman ini!');
    }
    // --------------------------------------------------------------------------------
}
// ------------------------------------------------------------------------------------