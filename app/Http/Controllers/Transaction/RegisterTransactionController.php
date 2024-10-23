<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Services\Accounts\AccountsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

use Exception;

class RegisterTransactionController extends Controller
{
    protected $service;

    public function __construct(AccountsService $service)
    {
        $this->service = $service;
    }

    public function __invoke(TransactionRequest $request): JsonResponse
    {
        try {
            $transaction = $this->service->transaction($request->all());
            return response()->json([$transaction], JsonResponse::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}