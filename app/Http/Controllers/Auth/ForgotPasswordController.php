<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ForgotPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class ForgotPasswordController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'email' => ['required', 'email', 'exists:users,email'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $email = $validatedData['email'];
        $token = Str::random(60);

        try {
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]
            );

            $user = User::where('email', $email)->first();
            $user->notify(new ForgotPasswordNotification($token, $email));

            return response()->json(
                ['message' => __('passwords.sent')],
                JsonResponse::HTTP_OK
            );
        } catch (\Exception $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                $e->getCode() ?: JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}