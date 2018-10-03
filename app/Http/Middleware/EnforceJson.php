<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AuthAPI;
use App\Http\Controllers\API\UserAPIController;
use Illuminate\Support\Facades\Route;
use DateTime;
use App\Transformers\Json;
use Symfony\Component\HttpFoundation\HeaderBag;

class EnforceJson
{
    /**
     * Enforce json
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
          $request->server->set('HTTP_ACCEPT', 'application/json');
          $request->headers = new HeaderBag($request->server->getHeaders());
          //dd($request);
          return $next($request);
    }
}
