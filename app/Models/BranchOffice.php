<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use MatanYadaev\EloquentSpatial\Objects\Point;

class BranchOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'manager_id',
        'region_id',
    ];

    protected $casts = [
       'location' => Point::class,
    ];

    public function sellers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'branch_office_seller', 'branch_office_id', 'seller_id');
    }
}