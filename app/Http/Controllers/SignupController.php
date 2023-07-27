<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Signup;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\SignupResource;

class SignupController extends Controller
{
    //
    public function index()
    {
        
        $customer = Signup::all();
        $response = [
            'code' => 200, 
            'message' => 'Customers successfuly retrieved!',
            'customers' => SignupResource::collection($customer)];

        return $response;
    }
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

        $customer= new Signup;
        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->password = Hash::make($request->input('password'));
        $customer->save();

        $token = $customer->createToken('customerToken')->accessToken;
        $response = ['token' => $token, 'message' => 'Customer successfully created', 'user' => $customer];
        return $response;
    }

    public function destroy(string $id)
    {
        //
        $customer = Signup::findOrFail($id);
        $customer -> delete();
        $response = [
            'code' => 200, 
            'message' => 'Customer successfuly deleted!',
            'customer' => new SignupResource($customer)];

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
        $customer = Signup::where('email', $request->email)->first();

        if ($customer) {
            $check_password = Hash::check($request->password, $customer->password);
            if ($check_password) {
                $token = $customer->createToken('customerToken')->accessToken;
                $response = ['token' => $token, 'message' => 'Customer successfully logged in', 'user' => $customer]; 
                return $response;
            } else {
                return response(['error' => 'Password is invalid!'], 422);
            }
        } else {
            return response(['error' => 'Customer does not exist'], 422);
        }

    }

    public function logout(Request $request){
        $token = $request->customer()->token();
        $token->revoke();
        $response = ['message'=>'Customer successfully logged out'];
        return $response;
    }
}
