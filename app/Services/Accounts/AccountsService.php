<?php

namespace App\Services\Accounts;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Accounts\AccountsRepositoryContract;
use Illuminate\Support\Facades\Log;

class AccountsService implements AccountsServiceContract
{
    protected $repository;

    public function __construct(AccountsRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function createAccount(array $data): array
    {
        try {
            $account = $this->repository->create($data);
            return $account->toArray();
        } catch (\Exception $e) {
            Log::error('Error creating account: ' . $e->getMessage());
            throw new \Exception(__('exception.create_user'));
        }
    }

    public function getAccount(int $accountId): Collection
    {
        return $this->repository->findWhere(['number_account' => $accountId]);
    }

    public function transaction(array $data): array
    {
        try {
            $accountId = $data['accounts_id'];
            $paymentMethod = $data['payment_method'];
            $value = $data['value'];

            $account = $this->repository->find($accountId);
            $taxa = $this->calculateRate($paymentMethod, $value);

            $total = $value  + $taxa;

            if ($account->balance < $total) {
                throw new \Exception(__('exception.insufficient_balance'));
            }

            $account->balance -= $total;
            $account->save();

            $transaction = Transaction::create([
                'accounts_id' => $accountId,
                'payment_method' => $paymentMethod,
                'value' => $value,
            ]);

            return ['number_account' => $account->number_account, 'balance' => $account->balance, 'transaction' => $transaction];
        } catch (\Exception $e) {
            Log::error('Error creating account: ' . $e->getMessage());
            return ['message' => $e->getMessage()];
        }
    }

    private function calculateRate($payment_method, $value)
    {
        return match ($payment_method) {
            'D' => $value * 0.03,
            'C' => $value * 0.05,
            'P' => 0,
        };
    }
}