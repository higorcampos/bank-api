<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepositoryEloquent extends BaseRepository implements UserRepositoryContract
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function getUsersByRoles(array $roles, int $pageSize): LengthAwarePaginator
    {
        return $this->model->whereIn('role', $roles)->orderBy('name', 'ASC')->paginate($pageSize);
    }
}