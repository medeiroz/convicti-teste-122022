<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{actingAs, artisan};

uses(RefreshDatabase::class)
    ->beforeEach(fn () => artisan('import:project-data'));


test('Successfuly', function () {
    $user = User::factory()->seller()->create();
    $sale = [
        'description' => 'description',
        'price' => 100,
        'sold_at' => now()->format('Y-m-d H:i:s'),
        'location' => [
            'latitude' => -30.1141,
            'longitude' => -51.3281,
        ],
    ];
    actingAs($user)
        ->postJson(route('api.sales.store'), $sale)
        ->assertSuccessful();
});

test('Not Seller', function () {
    $user = User::factory()->generalDirector()->create();
    $sale = [
        'description' => 'description',
        'price' => 100,
        'sold_at' => now()->format('Y-m-d H:i:s'),
        'location' => [
            'latitude' => -30.1141,
            'longitude' => -51.3281,
        ],
    ];
    actingAs($user)
        ->postJson(route('api.sales.store'), $sale)
        ->assertUnauthorized()
        ->assertJsonFragment(['message' => 'You are not a seller']);
});
