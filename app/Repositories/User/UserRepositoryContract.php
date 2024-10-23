<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryContract extends BaseRepositoryContract
{
    public function getUsersByRoles(array $role, int $pageSize): LengthAwarePaginator;
}