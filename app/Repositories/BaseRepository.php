<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class BaseRepository
{
    public function __construct(
        private readonly Model $model
    ) {}

    public function getModel(): Model
    {
        return $this->model;
    }

    public function create(array $data): Model
    {
        $sanitizedData = Arr::only($data, $this->getModel()->getFillable());
        return $this->getModel()->create($sanitizedData);
    }

    public function find(int $id): ?Model
    {
        return $this->getModel()->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->getModel()->findOrFail($id);
    }

    public function update(int $id, array $data): bool
    {
        return $this->findOrFail($id)->update($data);
    }
}
