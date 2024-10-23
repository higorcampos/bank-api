<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountsRequest;
use App\Services\Accounts\AccountsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use Exception;

class RegisterAccountsController extends Controller
{
    protected $service;

    public function __construct(AccountsService $service)
    {
        $this->service = $service;
    }

    public function __invoke(AccountsRequest $request): JsonResponse
    {
        try {
            $this->service->createAccount($request->all());
            return response()->json(['message' => __('actions.created')], JsonResponse::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}