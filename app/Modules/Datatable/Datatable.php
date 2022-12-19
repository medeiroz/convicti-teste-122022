<?php

namespace App\Modules\Datatable;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

abstract class Datatable
{
    public abstract function getBuilder(): EloquentBuilder|BaseBuilder|Model;

    public function getList(): QueryBuilder
    {
        return QueryBuilder::for($this->getBuilder())
            ->defaultSort('-updated_at', 'id')
            ->allowedIncludes($this->getAllowedIncludes())
            ->allowedSorts($this->getAllowedSorts())
            ->allowedFilters($this->getAllowedFilters());
    }

    public function getPaginator(): LengthAwarePaginator
    {
        return $this->getList()
            ->paginate($this->perPage())
            ->withQueryString()
            ->onEachSide(0);
    }

    public function defaultPerPage(): int
    {
        return 10;
    }

    public function perPage(): int
    {
        return request()->input('per_page', $this->defaultPerPage());
    }

    public function getAllowedIncludes(): array
    {
        return explode(',', request()->input('include', ''));
    }

    public function getAllowedSorts(): array
    {
        $sorts = explode(',', request()->input('sort', ''));
        return array_map(function (string $sort) {
            $name = Str::startsWith('-', $sort) ? substr($sort, 1) : $sort;

            if (Str::contains($name, '.')) {
                return AllowedSort::callback($name, function($query, bool $descending, string $property) {
                    return $query->orderByPowerJoins($property, $descending ? 'desc' : 'asc');
                });
            }
            return $name;
        }, $sorts);
    }

    public function getAllowedFilters(): array
    {
        $filters = request()->input('filter');
        return (is_array($filters)) ? array_keys($filters) : [];
    }
}
