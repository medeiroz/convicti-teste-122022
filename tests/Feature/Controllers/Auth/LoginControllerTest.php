<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\{postJson};

uses(RefreshDatabase::class);

test('Successfully', function () {
    $user = User::factory()->create();
    $loginData = ['email' => $user->email, 'password' => 'password'];

    postJson(route('api.auth.login'), $loginData)
        ->assertSuccessful()
        ->assertJson(function (AssertableJson $json) {
            $json->has('message')
                ->has('token');
        });
});

test('Missing Password', function () {
    $user = User::factory()->create();
    $userData = $user->only(['email']);

    postJson(route('api.auth.login'), $userData)
        ->assertUnprocessable()
        ->assertJson(function (AssertableJson $json) {
            $json->has('message')
                ->has('errors.password');
        });
});
