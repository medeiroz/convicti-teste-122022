<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use MatanYadaev\EloquentSpatial\Objects\Point;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'branch_office_id',
        'roaming_branch_office_id',
        'location',
        'description',
        'price',
        'sold_at',
    ];

    protected $casts = [
        'price' => 'float',
        'sold_at' => 'datetime',
        'location' => Point::class,
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function branchOffice(): BelongsTo
    {
        return $this->belongsTo(BranchOffice::class, 'branch_office_id');
    }

    public function roamingBranchOffice(): BelongsTo
    {
        return $this->belongsTo(BranchOffice::class, 'roaming_branch_office_id');
    }
}
