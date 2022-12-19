<?php

namespace App\Http\Datatable\SaleDatatable;

use App\Models\Sale;
use App\Modules\Datatable\Datatable;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as BaseBuilder;

class SaleGeneralDirectorDatatable extends Datatable
{

    public function getBuilder(): EloquentBuilder|BaseBuilder|Model
    {
        return app(Sale::class)->with(['seller', 'roamingBranchOffice']);
    }
}
