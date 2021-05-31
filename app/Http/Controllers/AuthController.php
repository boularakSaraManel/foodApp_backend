<?php

namespace App\Http\Controllers;
 

use Illuminate\Http\Request;

use App\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    //

    public function register (Request $request){
        //
        $request->validate([
           'email' => 'required|email',
           'password' => 'required',
        ]); 

        //check if user already exists 
        $user= User::where('email', $request->email)->first();
        if ($user){
            /*throw ValidationException::withMessage([
               'email' => ['The provided email already exists.']
            ]);*/ 
            return response('The provided email already exists', 403); //403: operation forbiden
        }
         
        $input = $request->all(); //all input in one array
        $input['password'] = Hash::make($input['password']);
       
        $user = User::create($input);

        $response['token'] = $user->createToken($request->email)->plainTextToken;
        $response['user'] = $user;
        return response(json_encode($response), 201);

    }

    public function login (Request $request){
        //
        //$validator= 
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            //'device_name' => 'required',
        ]);
         
             //if($validator->fails)

        $user = User::where('email', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
           /* throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);*/
            return response('The provided credentials are incorrect', 403); //403: operation forbiden
        }
    
       //return $user->createToken($request->device_name)->plainTextToken;
       $response['token'] = $user->createToken($request->email)->plainTextToken;
       $response['user'] = $user;
       return response(json_encode($response));
       }


    public function logout (){
        //
    }


}
