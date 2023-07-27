<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResources;


class UserController extends Controller
{
    public function index()
    {
        //
        $user = User::all();
        $response = [
            'code' => 200, 
            'message' => 'User successfuly retrieved!',
            'users' => UserResources::collection($user)];

        return $response;
    }

    public function destroy(string $id)
    {
        //
        $user = User::findOrFail($id);
        $user -> delete();
        $response = [
            'code' => 200, 
            'message' => 'User successfuly deleted!',
            'users' => new UserResources($user)];

        return $response;
    }
    //
    function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()->all()], 422);
        }

        $user= new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $token = $user->createToken('userToken')->accessToken;
        $response = ['token' => $token, 'message' => 'User successfully created', 'user' => $user];
        return $response;
    }

    function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            
            return response(['error' => $validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $check_password = Hash::check($request->password, $user->password);
            if ($check_password) {
                $token = $user->createToken('userToken')->accessToken;
                $response = ['token' => $token, 'message' => 'User successfully logged in', 'user' => $user]; 
                return $response;
            } else {
                return response(['error' => 'Password is invalid!'], 422);
            }
        } else {
            return response(['error' => 'User does not exist'], 422);
        }

        // if(!$user || !Hash::check($request->password, $user->password)){
        //     return ['error' => 'Email or password is incorrect'];
        // }
        // $token = $user->createToken('LaravelTokenPassword')->accessToken;
        // $response = ['token' => $token, 'message' => 'User successfully logged in', 'user' => $user]; 
        // return $response;
    }

    public function logout(Request $request){
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message'=>'User successfully logged out'];
        return $response;
    }
}
