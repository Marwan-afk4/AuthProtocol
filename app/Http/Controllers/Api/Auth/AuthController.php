<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{


    public function register(Request $request)
{
    $validation = Validator::make($request->all(), [
        'name' => 'required|string',
        'email' => 'required|email',
        'phone' => 'required',
        'password' => 'required|min:8',
    ]);

    if ($validation->fails()) {
        return response()->json($validation->errors(), 422);
    }

    // Check for existing user by email or phone
    $userExists = User::where('email', $request->email)
                      ->orWhere('phone', $request->phone)
                      ->first();

    $code = rand(100000, 999999); // Email verification code

    // If user exists and is not verified, update the record
    if ($userExists) {
        if ($userExists->is_email_verified == false) {
            $userExists->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'email_verification_code' => $code,
                'is_email_verified' => false,
            ]);

            Mail::to($userExists->email)->send(new EmailVerificationCode($code, $userExists->name));

            return response()->json([
                'message' => 'Go and check your email to verify your account',
            ]);
        } elseif($userExists->is_email_verified == true) {
            return response()->json([
                'message' => 'This email or phone number is already registered',
            ], 409);
        }
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
        'role' => 'user',
        'email_verification_code' => $code,
        'is_email_verified' => false,
    ]);

    Mail::to($user->email)->send(new EmailVerificationCode($code, $user->name));

    return response()->json([
        'message' => 'Go and check your email to verify your account',
    ]);
}



    public function verifyEmail(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'code' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user->email_verification_code !== $request->code) {
            return response()->json(['error' => 'Invalid verification code.'], 400);
        }

        $user->update([
            'is_email_verified' => true,
            'email_verification_code' => null,
            'account_status' => 'active',
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;


        return response()->json(['message' => 'Email verified successfully.',
            'user' => $user,
            'token' => $token,
        ]);
    }



    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'nullable|email|exists:users,email',
            'phone' => 'nullable|exists:users,phone',
            'password' => 'required|min:8',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 422);
        }


        $user = User::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();
        if($user->is_email_verified == false){
            return response()->json([
                'message' => 'Please verify your email first',
            ], 401);
        }
        elseif($user->is_email_verified == true){
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'User successfully logged in',
                'user' => $user,
                'token' => $token,
            ]);
        }


    }
}
