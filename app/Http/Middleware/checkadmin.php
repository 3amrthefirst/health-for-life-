<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; 
use App\Models\AdminModel;
use App;

class checkadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard =null)
    {
        if(Auth::guard('admin')->user()->role_id ==2){
            
            return response()->json(array('status' => 400, 'errors'=>("This User Is Not Access Your Pages"))); 
        }
        return $next($request);
    }
}
