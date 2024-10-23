<?php

namespace App\Http\Controllers\EmailVerification;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Notifications\VerifyEmailNotification;
use App\Repositories\User\UserRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use Exception;

class SendVerificationController extends Controller
{

    protected $userRepository;

    public function __construct(UserRepositoryContract $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke($userId): JsonResponse
    {
        try {
            $token = EmailVerification::generateToken($userId);
            $user = $this->userRepository->find($userId);
            $user->notify(new VerifyEmailNotification($token));

            return response()->json(['message' => __('actions.send')], JsonResponse::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}