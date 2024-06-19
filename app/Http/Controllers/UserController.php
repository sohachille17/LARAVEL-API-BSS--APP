<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
           return User::where([['email', '!=', 'gildassob@gmail.com'],['email', '!=', 'achillesoh15@gmail.com']])->get();
    }



    public function store(Request $request)
    {
 
          $feilds = $request->validate(
            [
                'name'=>'required|string',
                'email'=>'required|string|unique:users,email',
                'password' => 'required|string|confirmed',
                'role' => 'in:admin,financial,commercial',

            ]);


            $feilds = array_merge($feilds, ['role' => $feilds['role'] ?? 'admin']);


            $user = User::create(
                [
                    'name'=> $feilds['name'],
                    'email'=>$feilds['email'],
                    'password' => bcrypt($feilds['password']),
                    'role' => $feilds['role']
                ]
                );


        $response = [
            'user'=>$user,
        ];

        return Response($response,200);
    }

    public function show($id)
    {
        $user = User::find($id);

        return [
            "user"=>$user,
            "message"=>$user?"":"no user found",
            "status"=>"success"
        ];
    }


    public function update(Request $request, $id)
    {

        $feilds = $request->validate(
            [
                'name'=>'string',
                'email'=>'string|unique:users,email',
                'role' => 'in:admin,financial,commercial',

            ]);

        $user =  User::find($id);

        $user->update($feilds);

        return $user;
    }


    public function destroy($id)
    {
        $user =  User::where('id',$id)->update(['status'=>'blocked']);
        return Response(json_encode($user),200);
    }

    public function count(){
		$numUsers = User::count();
        return $numUsers?$numUsers:0;
    }
}
