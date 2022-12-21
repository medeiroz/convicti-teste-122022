<?php

use App\Models\User;
use App\Repositories\RegionRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

test('Create', function () {
    $user = User::factory()->make([
        'name' => 'Flavio Medeiros',
        'email' => 'smedeiros.flavio@gmail.com',
        'password' => 'long-hash'
    ]);

    Hash::shouldReceive('make')
        ->once()
        ->with('password')
        ->andReturn('long-hash');

    $mockUserModel = mock(User::class)
        ->shouldReceive('create')
        ->once()
        ->with([
            'name' => 'Flavio Medeiros',
            'email' => 'smedeiros.flavio@gmail.com',
            'password' => 'long-hash',
        ])
        ->andReturn($user)
        ->getMock();

    $mockUserModel->shouldReceive('getFillable')
        ->once()
        ->andReturn(['name', 'email', 'password', 'role']);

    $userRepository = new UserRepository($mockUserModel);

    $user = $userRepository->create([
        'name' => 'Flavio Medeiros',
        'email' => 'smedeiros.flavio@gmail.com',
        'password' => 'password',
    ]);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->name)->toEqual('Flavio Medeiros')
        ->and($user->email)->toEqual('smedeiros.flavio@gmail.com')
        ->and($user->password)->toEqual('long-hash');

});
