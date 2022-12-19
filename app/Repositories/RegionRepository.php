<?php

namespace App\Repositories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Model;

class RegionRepository extends BaseRepository
{
    public function __construct(
        Region $model,
    )
    {
        parent::__construct($model);
    }

    public function findByName(string $name): ?Model
    {
        return $this->getModel()->whereName($name)->first();
    }
}
