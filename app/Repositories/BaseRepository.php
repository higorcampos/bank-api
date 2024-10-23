<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseRepository implements BaseRepositoryContract
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $columns
     * @return Collection
     */
    public function all($columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    /**
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function find($id, $columns = ['*']): Model
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param array $columns
     * @return Collection
     */
    public function findByField($field, $value = null, $columns = ['*']): Collection
    {
        return $this->model->where($field, '=', $value)->get($columns);
    }

    /**
     * @param array $where
     * @param array $columns
     * @return Collection
     */
    public function findWhere(array $where, $columns = ['*']): Collection
    {
        $query = $this->model->select($columns);

        foreach ($where as $field => $value) {
            $query->where($field, $value);
        }

        $results = $query->get();

        if ($results->isEmpty()) {
            throw new ModelNotFoundException('No results found for the given conditions.');
        }

        return $results;
    }

    /**
     * @param array $where
     * @param array $columns
     * @return Model
     */
    public function findWhereIn($field, array $values, $columns = ['*']): Collection
    {
        return $this->model->whereIn($field, $values)->get($columns);
    }

    /**
     * @param array $where
     * @param array $columns
     * @return Model
     */
    public function findWhereNotIn($field, array $values, $columns = ['*']): Collection
    {
        return $this->model->whereNotIn($field, $values)->get($columns);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function findWhereBetween($field, array $values, $columns = ['*']): Collection
    {
        return $this->model->whereBetween($field, $values)->get($columns);
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, string $id): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->model->where('id', $id)->delete();
    }

    /**
     * @param array $attributes
     * @param array $values
     * @return Model
     */
    public function updateOrCreate(array $attributes, array $values): Model
    {
        return $this->model->updateOrCreate($attributes, $values);
    }
}
