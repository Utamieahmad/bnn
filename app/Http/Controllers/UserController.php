<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\View_users as View_users;
use Illuminate\Http\Request;
use Validator;
use App\Models\rbac_users as Rbac_Users;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Contracts\Filesystem\Factory;
use File;
class UserController extends Controller
{	
	public $data = array();

    public function profile(Request $request){
    	$user_id = Auth::user()->user_id;
        $data_profile = View_users::where('user_id',$user_id)->first();
        $data_additional = Rbac_Users::where('user_id', $user_id)->first();
        if(File::exists($data_profile->user_profile)){
            $this->data['img_path'] = $data_profile->user_profile;
        }else{
            $this->data['img_path'] = 'assets/images/default-image.png';
        }
        $this->data['data_profile'] = $data_profile;
        $this->data['data_additional'] = $data_additional;
        return view('auth.profile',$this->data);
    }

    public function user_management(){
      return view('my_profile/user_management');
    }

    public function user_management_add(){
      return view('my_profile/add');
    }

    public function upload_photo(Request $request){
		$validator = Validator::make($request->all(),
			[
			'file' => 'image',
			],
			[
			'file.image' => 'The file must be an image (jpeg, png, bmp, gif, or svg)'
			]);
		if ($validator->fails())
			return array(
			'fail' => true,
			'errors' => $validator->getMessageBag()->toArray()
		);
        $extension = $request->file('file')->getClientOriginalExtension(); // getting image extension
        // $dir = 'uploads/';
        $user_id = Auth::user()->user_id;
        $filename = $user_id . '_' . time() . '.' . $extension;
        $directory = 'public/profile';
        $directories = Storage::directories();
        if(!in_array($directory, $directories)){
            Storage::makeDirectory($directory);
        }
        $path = Storage::putFile($directory, $request->file('file'));
        $img_path =  str_replace('public', 'storage', $path);
        if(File::exists($img_path)){
            $image = $img_path;
        }else{
            $image = 'assets/images/default-image.png';
        }
        $db_path = str_replace('public', '', $img_path);
        if($path){
            $this->data['data'] = [];
            $update = Rbac_Users::where('user_id',$user_id)->update(['user_profile'=>$db_path]);
            if($update){
                $this->data['status'] = "success";
                $this->data['message'] = "Foto berhasil di simpan.";
                $this->data['data'] = ['path'=>$image];
            }else{
                $this->data['status'] = "failed";
                $this->data['message'] = "Foto gagal di simpan.";
            }
        }else{
            $this->data['status'] = "failed";
            $this->data['message'] = "Foto gagal di simpan.";
        }
        return response()->json($this->data);
    }
}
