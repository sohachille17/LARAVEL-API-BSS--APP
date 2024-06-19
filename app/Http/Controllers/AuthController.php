<?php

namespace App\Http\Controllers;

use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        // 1 verify and validate incomming data
        $feilds = $request->validate(
            [
                'name'=>'required|string',
                'email'=>'required|string|unique:users,email',
                'password' => 'required|string|confirmed',
                'role' => 'in:admin,financial,commercial',

            ]);



        // watch out for the line below
        // Set default role to 'admin' if not provided in the request
        $feilds = array_merge($feilds, ['role' => $feilds['role'] ?? 'admin']);


        // 2 create user from data
        $user = User::create(
            [
                'name'=> $feilds['name'],
                'email'=>$feilds['email'],
                'password' => bcrypt($feilds['password']),
                'role' => $feilds['role']
            ]
            );

        // 3 create token from user data for that specific user
        $token = $user->createToken('BssAppToken')->plainTextToken;

        $response = [
            'user'=>$user,
            'token'=>$token
        ];

        // 4 send back the response information
        return Response($response,200);

    }


    public function resetPassword(Request $request,$userId){
        // 1 verify and validate incomming data
        $feilds = $request->validate(
            [
                'password' => 'required|string|confirmed',
            ]);



        // 2 find the user from data
        $checkUser= User::where("id","=",$userId)->get()->first();

        if(!$checkUser){
            return Response(["message"=>"the user you are trying to update doesnt exist"],404);
        }

        // 2 if user exists update his password with the new password
        $user = $checkUser->update(
            [
                'password' => bcrypt($feilds['password']),
            ]);

        // 3 create token from user data for that specific user
        $token = $checkUser->createToken('BssAppToken')->plainTextToken;

        $response = [
            'updatedUser'=>$checkUser,
            'token'=>$token,
            'now login with the password you provided'
        ];

        // 4 send back the response information
        return Response($response,200);

    }

    public function login(Request $request){

        // 1 verify and validate incomming data
        $feilds = $request->validate(
            [
                'email'=>'required|string',
                'password' => 'required|string'
            ]);

        // 2 check email from data
        $user = User::where('email',$feilds['email'])->first();


        // 3 check password
           if(!$user || !Hash::check($feilds['password'],$user->password)){
                return Response([
                    'error'=>'username or password wrong'
                ],401);
           }

        // 4 check if user has been blocked
        if($user->status == 'blocked'){
        return Response([
        'error'=>'You cannot login with this credentials,Contact the administrators for more information'
        ],404);
        }

        // 3 create token from user data for that specific user
        $token = $user->createToken('BssAppToken')->plainTextToken;

        $user['token'] = $token;


        // $response = [
        //     $user,
        // ];

        // 4 send back the response information
        return Response($user,200);

    }

    public function logout(Request $request){
        $action = auth()->user()->tokens()->delete();

        $response =  [
            'status'=> $action,
            'message'=>'logout'
        ];

        return $response;
    }
}
