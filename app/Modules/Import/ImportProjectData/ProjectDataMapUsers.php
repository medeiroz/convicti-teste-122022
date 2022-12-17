<?php

namespace App\Modules\Import\ImportProjectData;

use App\Modules\Import\ImportProjectData\Enum\RecordEnum;
use App\Modules\Import\ImportProjectData\Enum\RoleEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Str;
use function PHPUnit\Framework\isEmpty;

class ProjectDataMapUsers
{
    public function map(Collection $collection): Collection
    {
        $users = collect();
        $recordRole = null;

        $collection->each(function (Collection $row) use ($users, &$recordRole) {
            if ($row->isEmpty() && !isEmpty($row->first())) {
                return;
            }

            if ($this->getRecordRole($row->first())) {
                $recordRole = $this->getRecordRole($row->first());
                return;
            }

            $user = $this->mapUser($row, $recordRole);
            if ($user) {
                $users->push($user);
            }
        });

        return $users;
    }

    protected function getRecordRole(?string $firstCol): ?RoleEnum
    {
        $role = $firstCol === 'Unidade' ? 'Gerente' : $firstCol;

        return RoleEnum::tryFrom($role);
    }

    protected function mapUser(Collection $row, ?RoleEnum $roleEnum): ?Collection
    {
        return match ($roleEnum) {
            RoleEnum::DIRETOR_GERAL => $this->mapDiretorGeral($row),
            RoleEnum::DIRETOR_REGIONAL => $this->mapDiretorRegional($row),
            RoleEnum::GERENTE => $this->mapGerente($row),
            RoleEnum::VENDEDOR => $this->mapVenderdor($row),
            default => null,
        };
    }

    protected function mapDiretorGeral(Collection $row): Collection
    {
        return collect([
            'roleEnum' => RoleEnum::DIRETOR_REGIONAL,
            'name' => $row[0],
            'email' => $row[1],
            'password' => $row[1],
        ]);
    }

    protected function mapDiretorRegional(Collection $row): Collection
    {
        return collect([
            'roleEnum' => RoleEnum::DIRETOR_REGIONAL,
            'region' => $row[0],
            'name' => $row[1],
            'email' => $row[2],
            'password' => $row[2],
        ]);
    }

    protected function mapGerente(Collection $row): Collection
    {
        return collect([
            'roleEnum' => RoleEnum::GERENTE,
            'branchOffice' => $row[0],
            'coordinates' => [
                'latitude' => trim(Str::before($row[1], ',')),
                'longitude' => trim(Str::after($row[1], ',')),
            ],
            'name' => $row[2],
            'region' => $row[3],
            'email' => $row[4],
            'password' => $row[4],
        ]);
    }

    protected function mapVenderdor(Collection $row): Collection
    {
        return collect([
            'roleEnum' => RoleEnum::VENDEDOR,
            'name' => $row[0],
            'branchOffice' => $row[1],
            'email' => $row[2],
            'password' => $row[2],
        ]);
    }
}
