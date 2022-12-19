<?php

namespace App\Modules\Import\ImportProjectData;

use App\Modules\Import\ImportProjectData\Enum\RoleEnum;
use Illuminate\Support\Collection;
use Str;

class ProjectDataMapUsers
{
    public function map(Collection $collection): Collection
    {
        $users = collect();
        $recordRole = null;

        $i = 0;

        $collection->each(function (Collection $row) use ($users, &$recordRole, &$i) {
            if ($row->isEmpty() || empty($row->first()) ) {
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
        $role = match($firstCol) {
            'Nome Diretoria' => 'Diretor',
            'Unidade' => 'Gerente',
            default => $firstCol,
        };

        return RoleEnum::tryFrom($role);
    }

    protected function mapUser(Collection $row, ?RoleEnum $roleEnum): ?Collection
    {
        return match ($roleEnum) {
            RoleEnum::GENERAL_DIRECTOR => $this->mapGeneralDirector($row),
            RoleEnum::REGIONAL_DIRECTOR => $this->mapRegionalDirector($row),
            RoleEnum::MANAGER => $this->mapManager($row),
            RoleEnum::SELLER => $this->mapSeller($row),
            default => null,
        };
    }

    protected function mapGeneralDirector(Collection $row): Collection
    {
        return collect([
            'roleEnum' => RoleEnum::GENERAL_DIRECTOR,
            'name' => $row[0],
            'email' => $row[1],
            'password' => $row[1],
        ]);
    }

    protected function mapRegionalDirector(Collection $row): Collection
    {
        return collect([
            'roleEnum' => RoleEnum::REGIONAL_DIRECTOR,
            'region' => $row[0],
            'name' => $row[1],
            'email' => $row[2],
            'password' => $row[2],
        ]);
    }

    protected function mapManager(Collection $row): Collection
    {
        return collect([
            'roleEnum' => RoleEnum::MANAGER,
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

    protected function mapSeller(Collection $row): Collection
    {
        return collect([
            'roleEnum' => RoleEnum::SELLER,
            'name' => $row[0],
            'branchOffice' => $row[1],
            'email' => $row[2],
            'password' => $row[2],
        ]);
    }
}
