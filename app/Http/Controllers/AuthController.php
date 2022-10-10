<?php
   
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use JWTFactory;
use JWTAuth;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
   
        if($validator->fails()){
            return $this->ErrorResponse($validator->errors()->toJson(), $validator->errors(),400);
            //return response()->json($validator->errors()->toJson(), 400);       
        }
   
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        //$success['token'] =  $user->createToken('Created')->accessToken;
        //$success['name'] =  $user->first_name;
        $token = JWTAuth::fromUser($user);
   
        return $this->SuccessResponse($token, $user,'User register successfully.');
    }
    public function login(Request $request)
    {
        // if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
        //     // $user = Auth::user(); 
        //     // $success['token'] =  $user->createToken('Login')-> accessToken; 
        //     //$success['name'] =  $user->name;
   
        //     return $this->SuccessResponse($success, $user, 'User login successfully.');
        // } 
        // else{ 
        //     return $this->ErrorResponse('Unauthorised.', ['error'=>'Unauthorised']);
        // } 
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        if ($validator->fails()) {
            return $this->ErrorResponse($validator->errors()->toJson(), $validator->errors(),422);
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);
            return $this->SuccessResponse($token, $user,'User loged in successfully.');
        }
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
             return $this->ErrorResponse('You are Unauthorised User.', ['error'=>'Unauthorised User'], 401);
            //return response()->json(['error' => 'Unauthorized'], 401);
        }

    }
}