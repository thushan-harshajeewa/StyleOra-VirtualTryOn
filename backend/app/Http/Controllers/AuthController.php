<?php

namespace App\Http\Controllers;

use App\Mail\UserVerification;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Services\EmailService;
use Exception;

class AuthController extends Controller
{

    protected $emailService;

    // Inject EmailService into the controller
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    
public function register(Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);


    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors(), // Return validation errors
        ], 422); // Unprocessable Entity HTTP status code
    }

    

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    if ($user) {
        // Send verification email
        $isSent = $this->emailService->sendVerificationEmail($user);

        // Return response
        if ($isSent) {
            return response()->json(['message' => 'User registered. Please verify your email.'], 201);
        } else {
            // Delete the user if email sending failed
            $user->delete();
            return response()->json(['error' => 'Unable to send verification email.'], 500);
        }
    }
    else {
        return response()->json(['error' => 'Cannot Register user.'], 500);
    }

    //$user->sendEmailVerificationNotification();

    // $token = JWTAuth::fromUser($user);

    // return response()->json(['token' => $token], 201);
}

public function login(Request $request)
{
    
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    
    $credentials = $request->only('email', 'password');
    if (!$token = JWTAuth::attempt($credentials)) {
        return response()->json(['error' => 'Invalid email or password'], 401); 
    }

    
    return response()->json(['token' => $token], 200); 
}


public function verifyEmail($id, $hash)
{
    $user = User::findOrFail($id);

    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return response()->json(['message' => 'Invalid verification link.'], 403);
    }

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return response()->json(['message' => 'Email verified successfully.']);
}




public function showUserProfile()
{
    $user = JWTAuth::user(); // Get the authenticated user
    
    if ($user->isGoogleUser()) {
        // Logic for Google users
        return response()->json(['message' => 'Google user profile']);
    } else {
        // Logic for regular email/password users
        return response()->json(['message' => 'Regular user profile']);
    }
}

public function redirectToGoogle() {
    return Socialite::driver('google')->stateless()->with(['prompt' => 'select_account'])->redirect();
}

public function handleGoogleCallback() {
    $googleUser = Socialite::driver('google')->stateless()->user();
    $user = User::firstOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'password' => Hash::make(uniqid()),  // Generate a dummy password
        ]
    );

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }


    $token = JWTAuth::fromUser($user);

    return redirect()->away("http://localhost:5173/auth/google/callback?token=" . $token);
}

public function get_user()
{
    $data = User::select('id', 'name', 'email','email_verified_at','google_id',) // Include only specific fields
                ->get();

    return response()->json([
        'success' => true,
        'data' => $data,
    ]);
}



}
