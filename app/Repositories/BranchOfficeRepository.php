<?php

namespace App\Repositories;

use App\Models\BranchOffice;
use Illuminate\Database\Eloquent\Model;

class BranchOfficeRepository extends BaseRepository
{
    public function __construct(
        BranchOffice $model,
    )
    {
        parent::__construct($model);
    }

    public function attachSeller(int $branchOfficeId, int $userId): void
    {
        $branchOffice = $this->find($branchOfficeId);
        $branchOffice->sellers()->attach($userId);
    }

    public function findByName(string $name): ?Model
    {
        return $this->getModel()->whereName($name)->first();
    }
}
