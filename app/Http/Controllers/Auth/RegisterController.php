<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Exception;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
{
    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            return ResponseHelper::success(message: 'User has been registered successfully!', data: $user, statusCode: 201);
        }

        return ResponseHelper::errors(message: 'Unable to Register User! Please try again.', statusCode: 400);
    } catch (Exception $e) {
        \Log::error('Unable to Register User: ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
        return ResponseHelper::error(message: 'An error occurred while registering the user.', statusCode: 500);
    }
}

}
