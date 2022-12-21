<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\User;
use App\Modules\Import\ImportProjectData\Enum\RoleEnum;
use App\Repositories\SaleRepository;

class SalesService
{
    public function __construct(
        protected readonly SaleRepository $saleRepository,
    )
    {
    }

    public function checkUserCanShow(User $user, Sale|int $saleOrId): bool
    {
        if (RoleEnum::from($user->role) === RoleEnum::GENERAL_DIRECTOR) {
            return true;
        }

        $sale = $saleOrId instanceof Sale
            ? $saleOrId
            : $this->saleRepository->find($saleOrId);

        if ($sale->seller_id === $user->id) {
            return true;
        }

        return $user->getBranchOffices()->pluck('id')->contains($sale->branch_office_id);
    }
}
