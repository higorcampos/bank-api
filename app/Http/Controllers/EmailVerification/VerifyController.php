<?php

namespace App\Http\Controllers\EmailVerification;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Repositories\User\UserRepositoryContract;
use Illuminate\Http\JsonResponse;

class VerifyController extends Controller
{

    protected $userRepository;

    public function __construct(UserRepositoryContract $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function __invoke($token): JsonResponse
    {
        try {
            $verification = EmailVerification::where('token', $token)->firstOrFail();
            $this->userRepository->update(['email_verified_at' => now()], $verification->user_id);
            $verification->delete();

            return response()->json(['message' => __('auth.email_verified')], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => __('validation.custom.invalid_token')], 400);
        }
    }
}