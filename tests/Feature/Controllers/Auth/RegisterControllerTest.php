<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\{postJson};

uses(RefreshDatabase::class);

test('Successfully', function () {
    $user = User::factory()->make();
    $userData = $user->only(['name', 'email', 'password']);

    postJson(route('api.auth.register'), $userData)
        ->assertSuccessful()
        ->assertJson(function (AssertableJson $json) {
            $json->has('message')
                ->has('token');
        });
});

test('Missing Password', function () {
    $user = User::factory()->make();
    $userData = $user->only(['name', 'email']);

    postJson(route('api.auth.register'), $userData)
        ->assertUnprocessable()
        ->assertJson(function (AssertableJson $json) {
            $json->has('message')
                ->has('errors.password');
        });
});

test('Existent Email', function () {
    $user = User::factory()->create();
    $userData = $user->only(['name', 'email', 'password']);

    postJson(route('api.auth.register'), $userData)
        ->assertUnprocessable()
        ->assertJson(function (AssertableJson $json) {
            $json->has('message')
                ->has('errors.email');
        });
});
