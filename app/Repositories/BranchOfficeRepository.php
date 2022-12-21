<?php

namespace App\Repositories;

use App\Models\BranchOffice;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;

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

    public function findByName(string $name): ?BranchOffice
    {
        return $this->getModel()->whereName($name)->first();
    }

    public function findNearByLatitudeAndLongitude(Point $location): ?BranchOffice
    {
        return $this->getModel()->query()->orderByDistance('location', $location)->first();
    }

    public function getRoamingBranchOfficeByLocation(User $seller, Point $location):?BranchOffice
    {
        $nearBranchOffice = $this->findNearByLatitudeAndLongitude($location);

        if ($seller->sellerBranchOffices()->first()->id !== $nearBranchOffice->id) {
            return $nearBranchOffice;
        }

        return null;
    }
}
