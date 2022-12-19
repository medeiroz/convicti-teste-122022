<?php

namespace App\Http\Datatable\SaleDatatable;

use App\Models\Sale;
use App\Models\User;
use App\Modules\Datatable\Datatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as BaseBuilder;

class SaleRegionalDirectorDatatable extends Datatable
{
    public function __construct(private readonly User $user)
    {
    }

    public function getBuilder(): EloquentBuilder|BaseBuilder|Model
    {
        return app(Sale::class)
            ->with(['seller', 'roamingBranchOffice'])
            ->whereHas('seller.sellerBranchOffices', function (Builder $builder) {
                $builder->whereIn('id', $this->user->regionalDirectorBranchOffices->pluck('id'));
            });
    }
}
