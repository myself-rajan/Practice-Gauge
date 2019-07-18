<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Auth;
use Closure;
use Session;

class checkSession 
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function handle($request, Closure $next)
    {
       //echo "Check Session";print_r(\Session::all());exit();
       if(Session::has('company') ){

            $check_company = Session::get('company');
            $user_id = Auth::user()->id;

            // if(isset($user_id)){

            // }else{
            //     return redirect(route('login'));
            // }
        }else{
              return redirect(route('select_organization'));
        }
       return $next($request);
    }
  
}
