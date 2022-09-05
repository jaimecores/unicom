<?php

namespace App\Http\Api\V1;

use App\Http\Api\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    /**
     * Register API
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required',
            'repeat_password' => 'required|same:password',
        ]);
   
        // Return errors if validation fails
        if($validator->fails()){
            return $this->returnError('The given data was invalid.', $validator->errors(), 422);       
        }
   
        // Get inputs from the requests and create the users
        $inputs = $request->all();
        $inputs['password'] = Hash::make($inputs['password']);
        $user = User::create($inputs);
        $result['token'] =  $user->createToken('UnicomApp')->plainTextToken;
   
        //Return the response with the token
        return $this->returnResponse($result, 'User registered successfully.');
    }
   
    /**
     * Login API
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Return errors if validation fails
        if($validator->fails()){
            return $this->returnError('The given data was invalid.', $validator->errors(), 422);       
        }

        // Find the user
        $user = User::where('email', $request->email)->first();
        
        // Check user and password
        if (!$user || !Hash::check($request->password, $user->password)) {

            // User unauthorised
            return $this->returnError('Unauthorised.', ['error'=>'User unauthorised.'], 401);
        } 
        else{ 

            // Create the token
            $result['token'] =  $user->createToken('UnicomApp')->plainTextToken; 
   
            //Return the response with the token
            return $this->returnResponse($result, 'User logged in successfully.');
        } 
    }
}
