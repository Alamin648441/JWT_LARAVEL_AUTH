<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPassword;
use App\Models\EmailOtp;
use App\Models\User;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class LoginController extends Controller
{
    use ApiResponse;

    public function sendOtp($user)
    {
        $code = rand(1000, 9999);

        // Store verification code in the database
        $verification = EmailOtp::updateOrCreate(
            ['email' => $user->email],
            [
                'verification_code' => $code,
                'expires_at' => Carbon::now()->addMinutes(5),
            ]
        );

        Mail::to($user->email)->send(new ForgetPassword($user, $code));
    }

    public function emailVerify(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        if (!$validator) {
            return $this->error([], 'Validation failed', 422);
        }

        try {
            $user = User::where('email', $request->email)->first();
            $this->sendOtp($user);
            return $this->success($user, '4 Digit OTP sent to your mail to verify your email.', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    public function otpResend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation Error', 422);
        }

        try {
            // Retrieve the user by email
            $user = User::where('email', $request->input('email'))->first();

            $this->sendOtp($user);

            return $this->success($user, 'OTP has been sent successfully.', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    public function otpVerify(Request $request)
    {
        // validate the request
        $validator = $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric|digits:4',
        ]);
        if (!$validator) {
            return $this->error([], 'Validation failed', 422);
        }
        try {
            $user = User::where('email', $request->email)->first();

            $verification = EmailOtp::where('email', $user->email)
                ->where('verification_code', $request->otp)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if ($verification) {
                $user->email_verified_at = Carbon::now();
                $user->save();

                $verification->delete();

                $token = JWTAuth::fromUser($user);

                $user->setAttribute('token', $token);

                return $this->success($user, 'OTP verified successfully', 200);
            } else {
                return $this->error([], 'Invalid or expired OTP', 400);
            }
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    public function userLogin(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if (!$validator) {
            return $this->error([], 'Validation failed', 422);
        }
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->error([], 'Invalid credentials', 401);
            }
            $user = auth()->user();

            if ($user->email_verified_at === NULL) {
                $this->sendOtp($user);

                return $this->success($user, 'please verify your email to login . 4 digit OTP send to your mail to verify your email.', 403);
            }

            $data = [
                'user' => $user,
                'token' => $token,
            ];
            return $this->success($data, 'Login successful', 200);
        } catch (JWTException $e) {
            return $this->error([], 'Invalid credentials', 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        
        $validator = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        if (!$validator) {
            return $this->error([], 'Validation failed', 422);
        }
        try {
            $user = User::where('email', $request->email)->first();
            $this->sendOtp($user);
            return $this->success($user, '4 Digit OTP sent to your mail to reset your password.', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }


    public function passChange(Request $request)
    {
       $user = JWTAuth::parseToken()->authenticate();
        $validator = $request->validate([
            'current_pass' => 'required|string|min:6',
            'new_pass' => 'required|string|min:6|confirmed',
        ]);
        if (!$validator) {
            return $this->error([], 'Validation failed', 422);
        }
        try {
            if(Hash::check($request->current_pass, $user->password)){
                $user->password = Hash::make($request->new_pass);
                $user->save();
                return $this->success($user, 'Password changed successfully', 200);
            }else{
                return $this->error([], 'Current password is incorrect', 400);
            } 
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }

    }

    public function resetPass(Request $request){

        $validator = $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);
        if (!$validator) {
            return $this->error([], 'Validation failed', 422);
        }
        try {
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();
            return $this->success($user, 'Password reset successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }

    }
    public function Logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->success([], 'User logged out successfully', 200);
        } catch (JWTException $e) {
            return $this->error([], 'Failed to logout, please try again.', 500);
        }
    }

}
