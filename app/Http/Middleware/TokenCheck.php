<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AuthAPI;
use App\Http\Controllers\API\UserAPIController;
use Illuminate\Support\Facades\Route;
use DateTime;
use App\Transformers\Json;

class TokenCheck
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
        if (isset($_SERVER["HTTP_AUTHORIZATION"])) {
            list($type, $data) = explode(" ", $_SERVER["HTTP_AUTHORIZATION"], 2);
            if (strcasecmp($type, "Bearer") == 0) {
              $api_token = $data;
            } else {
              $api_token = null;
            }
        } else {
          $api_token = null;
        }

        $d        = new DateTime();
        $today = $d->format('Y-m-d H:i:s');
        $data = AuthAPI::where('api_token', '=', $api_token)->first();
        $request['api_token'] = $api_token;
        $request['created_by'] = $data->user_id;

        if (!$data->status){
            return response()->json(Json::response(null, 'error', 'token expired'), 200);
        } else {

            if($data->expired_time > $today){
                return $next($request);
            } else {
                AuthAPI::where('id', $data->id)->update(['status' => FALSE]);
                return response()->json(Json::response(null, 'error', 'token expired'), 200);
            }

        }

    }
}
