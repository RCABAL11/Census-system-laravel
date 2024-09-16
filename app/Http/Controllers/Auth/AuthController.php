<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
{
    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            return ResponseHelper::success(message: 'User has been registered successfully!', data: $user, statusCode: 201);
        }

        return ResponseHelper::error(message: 'Unable to Register User! Please try again.', statusCode: 400);

    } catch (Exception $e) {
        \Log::error('Unable to Register User: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
        return ResponseHelper::error(message: 'An error occurred while registering the user.', statusCode: 500);
    }
}

public function login(LoginRequest $request)
{
    try{

        //If credential is invalid
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return ResponseHelper::error(message: 'Unable to Login due to invalid credentials.', statusCode: 401); 
        }

        $user = Auth::user();  

        // Creating API token
        $token = $user->createToken('My API Token')->plainTextToken;

        $authUser = [
            'user' => $user,
            'token' => $token
        ];
        return ResponseHelper::success(message: 'You are Logged in successfully!', data: $authUser, statusCode: 200);

    } 
    catch (Exception $e) {
        \Log::error('Unable to Login user: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
        return ResponseHelper::error(message: 'Unable to Login! Please try again.', statusCode: 500);
    }
}



    //Displaying user information
    public function userProfile()
    {
        try{

            
            $user = Auth::user();

            if($user) {
                return ResponseHelper::success(message: 'User profile fetched successfully!', data: $user, statusCode: 200);
            }
            return ResponseHelper::error(message: 'Unable to fetched user data due to invalid token.', statusCode: 400);

        }
        catch(Exception $e) {
            \Log::error('Unable to Fetch User Profile : ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'Unable to Fetch User Profile! Please try again.', statusCode: 500);
        }
    }

    //Logout user
    public function userLogout()
    {
        try{
        
            $user = Auth::user();

            if($user) {
                $user->currentAccessToken()->delete();
                return ResponseHelper::success(message: 'User logout successfully!', data: $user, statusCode: 200);
            }
            return ResponseHelper::error(message: 'Unable to logout due to invalid token.', statusCode: 400);

        }
        catch(Exception $e) {
            \Log::error('Unable to logout due to some exception : ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'Unable to logout due some exception! Please try again.' . $e->getLine(), statusCode: 500);
        }
    }


    }
