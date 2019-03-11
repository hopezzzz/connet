<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
class Role
{
      public function handle($request, Closure $next,$roles=null)
        {
        	$role_name=null;
            if(Auth::user()->roles()->count()!=0)
            {
              $role_name=Auth::user()->roles()->first()->name;   
            }
            if($role_name==$roles)
            {
            	return $next($request);
            }
             
            return redirect('/'.$role_name.'/');
        }
}
?>