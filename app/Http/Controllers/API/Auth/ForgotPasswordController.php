<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Transformers\Json;
use App\Models\rbac_users;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Mail;
use Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
     */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getResetToken(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
        if ($request->wantsJson()) {
            $user = Rbac_users::where('email', $request->input('email'))->first();
            if (!$user) {
                return response()->json(Json::response(null, "error"));
            }
            $body = $user->email;
            $token = $this->broker()->createToken($user);
            $data = array('reseturl' => url('/password/reset/'.$token),
                          'appname' => config('app.name'),
                          'homeurl' => config('app.url') );
            Mail::send('mail', $data, function($message) use ($body) {
               $message->to($body)
                        ->subject('Reset Password');
               $message->from('admin@gmail.com',config('app.name'));
            });
            return response()->json(Json::response(['token' => $token,'data'=>json_encode($data)], "success"));

        }else{
            return response()->json(Json::response(['error' => 'Failed to process request.']));
        }
    }

    public function getNewUserToken(Request $request) //untuk user yang baru ditambahkan
    {

      $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        // 'password' =>  'required',
      ]);

      if (!$validator->fails()) {
        if ($request->wantsJson()) {
          try{
            $user = Rbac_users::where('email', $request->input('email'))->first();
            if (!$user) {
                return response()->json(Json::response(null, "error"));
            }
            $body = $user->email;
            $token = $this->broker()->createToken($user);
            $data = array('reseturl' => url('/password/reset/'.$token),
                          'appname' => config('app.name'),
                          'homeurl' => config('app.url') );
            Mail::send('mail', $data, function($message) use ($body) {
               $message->to($body)
                        ->subject('New Password');
               $message->from('admin@gmail.com',config('app.name'));
            });
            return response()->json(Json::response(['token' => $token,'data'=>json_encode($data)], "success"));
          } catch(\Exception $e) {
              return response()->json(Json::response(null, 'error', $e->getMessage()), 200);

          }
        }else{
            return response()->json(Json::response(['error' => 'Failed to process request.']));
        }
      }else{
        return response()->json([
                'message'    => "Validation Failed",
                'status' => 'error',
                'data' => $validator->errors()->all()
            ]);
      }


    }

    // public function getNewUserToken(Request $request) //untuk user yang baru ditambahkan
    // {
    //     $this->validate($request, ['email' => 'required|email']);
    //     if ($request->wantsJson()) {
    //         $user = Rbac_users::where('email', $request->input('email'))->first();
    //         if (!$user) {
    //             return response()->json(Json::response(null, "error"));
    //         }
    //         $body = $user->email;
    //         $token = $this->broker()->createToken($user);
    //         $data = array('reseturl' => url('/password/reset/'.$token),
    //                       'appname' => config('app.name'),
    //                       'homeurl' => config('app.url') );
    //         Mail::send('mail_new_user', $data, function($message) use ($body) {
    //            $message->to($body)
    //                     ->subject('New Password');
    //            $message->from('admin@gmail.com',config('app.name'));
    //         });
    //         return response()->json(Json::response(['token' => $token,'data'=>json_encode($data)], "success"));
    //
    //     }else{
    //         return response()->json(Json::response(['error' => 'Failed to process request.']));
    //     }
    // }
}
