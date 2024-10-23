<?php

namespace App\Services\Accounts;

use Illuminate\Database\Eloquent\Collection;

interface AccountsServiceContract
{
    public function getAccount(int $accountId): Collection;
    public function createAccount(array $data): array;
    public function transaction(array $data): array;
}