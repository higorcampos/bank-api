<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface BaseRepositoryContract
{
    public function all(): Collection;
    public function find(int $id, array $columns = ['*']): Model;
    public function findByField(string $field, string $value = null, array $columns = ['*']): Collection;
    public function findWhere(array $where, $columns = ['*']): Collection;
    public function findWhereIn($field, array $values, $columns = ['*']): Collection;
    public function findWhereNotIn($field, array $values, $columns = ['*']): Collection;
    public function findWhereBetween($field, array $values, $columns = ['*']): Collection;
    public function create(array $data): Model;
    public function update(array $data, string $id): bool;
    public function delete(int $id): bool;
    public function updateOrCreate(array $attributes, array $values): Model;
}
