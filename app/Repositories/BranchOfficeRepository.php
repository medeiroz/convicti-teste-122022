<?php

namespace App\Repositories;

use App\Models\BranchOffice;

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
}
