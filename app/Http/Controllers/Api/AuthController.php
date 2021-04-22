<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
//            'device_name' => 'required',
        ]);

     $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $detail['id']=$user->id;
        $detail['fullName']=$user->name;
        $detail['username']=$user->name;
        $detail['avatar']=url("storage/$user->profile_photo_path");
        $detail['email']=$user->email;
        if($user->getRoleNames()){
            $detail['role']=$user->getRoleNames()[0];
        }else{
            $detail['role']='seller';
        }
        $detail['role']='client';
        $detail['ability']=[
            [
                'action'=>'manage',
                'subject'=>'all'
            ]
        ];
        $detail['extras']=[
            'eCommerceCartItemsCount'=>5
        ];


        $token=$user->createToken($request->device_name)->plainTextToken;

        $data['userData']=$detail;
        $data['accessToken']=$token;
        $data['refreshToken']=$token;
        return response()->json(['status'=>true,'message'=>'','data'=>$data]);
    }
}
