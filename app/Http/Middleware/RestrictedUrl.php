<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Session;

class RestrictedUrl 
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->isAllowed()) {
            //return abort(403, 'You are not authorized to access this. Please set client and project first');
            return redirect()->route('StepOne');
        }

        return $next($request);
    }

    protected function isAllowed(){
        return (Session::has('client_id') && Session::has('project_id'));
    }

}

