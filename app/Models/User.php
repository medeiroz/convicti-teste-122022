<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'seller_id');
    }

    public function sellerBranchOffices(): BelongsToMany
    {
        return $this->belongsToMany(BranchOffice::class, 'branch_office_seller', 'seller_id', 'branch_office_id');
    }

    public function regionalDirectorBranchOffices(): HasManyThrough
    {
        return $this->hasManyThrough(
            BranchOffice::class,
            Region::class,
            'director_id',
            'region_id',
        );
    }

    public function managerBranchOffice(): HasOne
    {
        return $this->hasOne(BranchOffice::class, 'manager_id');
    }
}
