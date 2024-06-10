<?php

namespace App\Http\Controllers;

use App\Classes\UserClass;
use Illuminate\Http\Request;
use  App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $authManager;
    public function __construct(UserClass $userClass)
    {
        $this->authManager = $userClass;
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ],[
            'name.required' => 'Name Filed is Required',
            'email.required' => 'Email Filed is Required',
            'password.required' => 'Password Filed is Required',
        ]);
        if ($validator->fails()){
            $error = $validator->errors()->first();
            return response()->json(['status'=>false,'message'=>$error])->setStatusCode(400);
        }
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        return $this->authManager->register($name,$email,$password);
    }
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'email' => 'required|string|email',
                'password' => 'required|string|min:8',
            ],[
                'email.required' => 'Email field is required!',
                'email.email' => 'Email must be a valid email address!',
                'password.required' => 'Password field is required!',
                'password.min' => 'Password must be at least 8 characters!',
            ]);

            if ($validator->fails()){
                $error = $validator->errors()->first();
                return response()->json(['status' => false, 'message' => $error], 400);
            }

            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }

            return response()->json(['status' => true, 'token' => $token], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Authentication failed!', 'error' => $exception->getMessage()], 400);
        }
    }
    public function authAlive()
{
    try {
        // Mengecek apakah ada token JWT yang valid
        if (Auth::guard('api')->check()) {
            return response()->json(["status" => true, "message" => "Auth Alive success"])->setStatusCode(200);
        }
        return response()->json(["status" => false, "message" => "Unauthorized"])->setStatusCode(401);
    } catch (\Exception $ex) {
        Log::info("AuthController", ["authAlive" => $ex->getMessage(), "line" => $ex->getLine()]);
        return response()->json(["status" => false, "message" => "Auth Alive Failed"])->setStatusCode(500);
    }
}
}
