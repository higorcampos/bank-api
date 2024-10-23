<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserServiceContract
{
    public function getAllUsers(): array;
    public function create(array $data): array;
    public function getUser(): User;
    public function getUserByRole(int $pageSize): LengthAwarePaginator;
}