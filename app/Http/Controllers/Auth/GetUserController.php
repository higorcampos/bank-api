<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\User\UserServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use Exception;

class GetUserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceContract $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(): JsonResponse
    {
        try {
            $user = $this->userService->getUser();
            return response()->json($user, JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error get user' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}