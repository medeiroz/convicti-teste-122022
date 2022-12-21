<?php

namespace App\Repositories;

use App\Http\Datatable\SaleDatatable\SaleGeneralDirectorDatatable;
use App\Http\Datatable\SaleDatatable\SaleDefaultDatatable;
use App\Http\Datatable\SaleDatatable\SaleSellerDatatable;
use App\Models\Sale;
use App\Models\User;
use App\Modules\Datatable\Datatable;
use App\Modules\Import\ImportProjectData\Enum\RoleEnum;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;

class SaleRepository extends BaseRepository
{
    public function __construct(
        Sale $model,
        protected readonly BranchOfficeRepository $branchOfficeRepository,
    )
    {
        parent::__construct($model);
    }

    public function getDatatableByUser(User $user): Datatable
    {
        return match (RoleEnum::from($user->role)) {
            RoleEnum::GENERAL_DIRECTOR => app(SaleGeneralDirectorDatatable::class),
            RoleEnum::SELLER => app(SaleSellerDatatable::class, ['user' => $user]),
            default => app(SaleDefaultDatatable::class, ['user' => $user]),
        };
    }

    public function create(array $data): Model
    {
        $user = auth()->user();
        $data['seller_id'] = $user->id;
        $data['location'] = new Point($data['location']['latitude'], $data['location']['longitude']);
        $data['branch_office_id'] = $user->sellerBranchOffices()->first()->id;
        $data['roaming_branch_office_id'] = $this
            ->branchOfficeRepository
            ->getRoamingBranchOfficeByLocation($user, $data['location'])
            ?->id;

        return parent::create($data);
    }
}
