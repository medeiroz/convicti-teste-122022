<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\{getJson};

uses(RefreshDatabase::class);

test('Successfully', function () {
    $user = User::factory()->create();
    $token = $user->createToken('AUTH')->plainTextToken;

    getJson(route('api.user'), ['Authorization' => 'Bearer ' . $token])
        ->assertSuccessful()
        ->assertJson($user->toArray());
});

test('Without token', function () {
    User::factory()->create();

    getJson(route('api.user'))
        ->assertUnauthorized()
        ->assertJson(function (AssertableJson $json) {
            $json->has('message');
        });
});

test('Invalid token', function () {
    $user = User::factory()->create();
    $token = '123456789';

    getJson(route('api.user'), ['Authorization' => 'Bearer ' . $token])
        ->assertUnauthorized()
        ->assertJson(function (AssertableJson $json) {
            $json->has('message');
        });
});
