<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    public function __construct(
        User $model,
    )
    {
        parent::__construct($model);
    }

    public function create(array $data): Model|User
    {
        $data['password'] = Hash::make($data['password']);
        return parent::create($data);
    }


}
