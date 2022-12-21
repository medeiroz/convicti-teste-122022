<?php

namespace App\Modules\Import\ImportProjectData;

use App\Models\User;
use App\Modules\Import\ImportProjectData\Enum\RoleEnum;
use App\Repositories\BranchOfficeRepository;
use App\Repositories\RegionRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Excel;
use MatanYadaev\EloquentSpatial\Objects\Point;
use stdClass;

class ImportProjectData
{
    public function __construct(
        protected readonly Excel                    $excel,
        protected readonly ProjectDataMapUsers      $mapUser,
        protected readonly UserRepository           $userRepository,
        protected readonly RegionRepository         $regionRepository,
        protected readonly BranchOfficeRepository   $branchOfficeRepository,
    )
    {
    }

    // @TODO WIP import users
    public function import(): void
    {
        $users = $this->getUsers();
        $this->createUsers($users);
    }

    protected function getUsers(): Collection
    {
        $data = $this->excel->toCollection(new stdClass, 'dados-do-projeto.xlsx');
        $sheet = $data->first();
        return $this->mapUser->map($sheet);
    }

    protected function createUsers(Collection $users): void
    {
        $users->map(fn ($user) => $this->createUser($user));
    }

    protected function createUser(Collection $userData): User
    {
        $data = $userData->only('name', 'email', 'password')->toArray();
        $data['role'] = $userData['roleEnum']->value;

        $userEloquent = $this->userRepository->create($data);

        match($userData['roleEnum']) {
            RoleEnum::REGIONAL_DIRECTOR
                => $this->createRegion($userData, $userEloquent),
            RoleEnum::MANAGER
                => $this->createBranchOffice($userData, $userEloquent),
            RoleEnum::SELLER
                => $this->attachSellerOnBranchOffice($userData, $userEloquent),
            default => null
        };

        return $userEloquent;
    }

    protected function createRegion(Collection $userData, User $userEloquent): void
    {
        if ($userData['region'] && !$this->regionRepository->findByName($userData['region'])) {
            $this->regionRepository->create([
                'name' => $userData['region'],
                'director_id' => $userEloquent->id,
            ]);
        }
    }

    protected function createBranchOffice(Collection $userData, User $userEloquent): void
    {
        if ($userData['branchOffice'] && !$this->branchOfficeRepository->findByName($userData['branchOffice'])) {
            $region = $this->regionRepository->findByName($userData['region']);

            $this->branchOfficeRepository->create([
                'name' => $userData['branchOffice'],
                'location' => new Point($userData['coordinates']['latitude'], $userData['coordinates']['longitude']),
                'manager_id' => $userEloquent->id,
                'region_id' => $region->id
            ]);
        }
    }

    protected function attachSellerOnBranchOffice(Collection $userData, User $userEloquent): void
    {
        $officeBranchId = $this->branchOfficeRepository->findByName($userData['branchOffice'])->id;
        $this->branchOfficeRepository->attachSeller($officeBranchId, $userEloquent->id);
    }
}
