<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\User_menuController;
use App\Http\Controllers\API\LoginAPIController;
use Illuminate\Support\Facades\Route;

class RolesCheck
{
    /* @author : Daniel Andi */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = (new LoginAPIController())->get_id($request->api_token);
        $request['menu'] = (new User_menuController())->get_usermenu($id)->original;

        return $next($request);

    }
}
