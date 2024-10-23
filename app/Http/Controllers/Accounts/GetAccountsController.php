<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Services\Accounts\AccountsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use Exception;

class GetAccountsController extends Controller
{
    protected $service;

    public function __construct(AccountsService $service)
    {
        $this->service = $service;
    }

    public function __invoke(int $accountId): JsonResponse
    {
        try {
            $account = $this->service->getAccount($accountId);
            return response()->json($account, JsonResponse::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error get account' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: JsonResponse::HTTP_NOT_FOUND);
        }
    }
}