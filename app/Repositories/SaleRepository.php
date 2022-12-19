<?php

namespace App\Repositories;

use App\Http\Datatable\SaleDatatable\SaleGeneralDirectorDatatable;
use App\Http\Datatable\SaleDatatable\SaleManagerDatatable;
use App\Http\Datatable\SaleDatatable\SaleRegionalDirectorDatatable;
use App\Http\Datatable\SaleDatatable\SaleSellerDatatable;
use App\Models\BranchOffice;
use App\Models\Sale;
use App\Models\User;
use App\Modules\Datatable\Datatable;
use App\Modules\Import\ImportProjectData\Enum\RoleEnum;
use Illuminate\Database\Eloquent\Model;

class SaleRepository extends BaseRepository
{
    public function __construct(Sale $model)
    {
        parent::__construct($model);
    }

    public function getDatatableByUser(User $user): Datatable
    {
        return match (RoleEnum::from($user->role)) {
            RoleEnum::GENERAL_DIRECTOR => app(SaleGeneralDirectorDatatable::class),
            RoleEnum::REGIONAL_DIRECTOR => app(SaleRegionalDirectorDatatable::class, ['user' => $user]),
            RoleEnum::MANAGER => app(SaleManagerDatatable::class, ['user' => $user]),
            RoleEnum::SELLER => app(SaleSellerDatatable::class, ['user' => $user]),
        };
    }

    public function create(array $data): Model
    {
        $data['seller_id'] = auth()->user()->id;
        $data['roaming_branch_office_id'] = $this->getRoamingBranchOfficeByLocation($data['location']);

        return parent::create($data);
    }


    public function getRoamingBranchOfficeByLocation(array $location):?BranchOffice
    {
        $latitude = $location['latitude'];
        $longitude = $location['longitude'];
        return null;
    }
}
