<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\MainModel;
use App\Models\Rbac_users;
use URL;
use DateTime;
use Carbon\Carbon;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Transformers\Json;
use Illuminate\Notifications\Messages\MailMessage;
use Mail;

class ResetEmailController extends Controller
{

    public function inputResetEmail(Request $request){
        $this->validate($request, ['email' => 'required|email', 'new_email' => 'required|email']);
        if ($request->wantsJson()) {
        	$email = $request->input('email');
        	$new_email = $request->input('new_email');
            $user = Rbac_users::where('email', $email)->first();
            if (!$user) {
                return response()->json(Json::response(null, "error"));
            }
            $body = $user->email;
            $token = str_random(255);
            Rbac_users::where('email', $email)->update(
            	array(
            		'email_token' => $token,
            		'temp_change_email' => trim($new_email)
            	)
            );
            $data = array('reseturl' => url('/reset_email/'.$token),
                          'appname' => config('app.name'),
                          'homeurl' => config('app.url') );
            Mail::send('mail_reset_email', $data, function($message) use ($body) {
               $message->to($body)
                        ->subject('Reset Email');
               $message->from('admin@gmail.com',config('app.name'));
            });
            return response()->json(Json::response(['token' => $token,'data'=>json_encode($data)], "success"));

        }else{
            return response()->json(Json::response(['error' => 'Failed to process request.']));
        }       
    }

    public function getResetEmail(Request $request){

        $token = $request->input('token');

        $user = Rbac_users::where('email_token', $token)->first();

        if($user)
        {
            $new_email = $user->temp_change_email;

            Rbac_users::where('email_token', $token)->update(array(
                'email' => trim($new_email),
                'email_token' => '',
                'temp_change_email' => ''
            ));

            return response()->json(Json::response(['token' => $token,'new_email'=>$new_email], "success"));
        }
        else
        {
            return response()->json(Json::response(['token' => $token,'data'=>$new_email], "error"));
        }

        
    }
}