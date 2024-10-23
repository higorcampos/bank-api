<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /**
     * Reset the given user's password.
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'email' => ['required', 'email', 'exists:users,email'],
                'token' => ['required', 'string'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $email = $validatedData['email'];
        $token = $validatedData['token'];
        $password = $validatedData['password'];;

        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => __('validation.custom.invalid_token')
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            return response()->json([
                'message' => __('validation.custom.invalid_token')
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where('email', $email)->first();

        $user->update([
            'password' => Hash::make($password),
        ]);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return response()->json([
            'message' => __('passwords.password_reset_success')
        ], JsonResponse::HTTP_OK);
    }
}