<?php

use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\{actingAs, artisan};

uses(RefreshDatabase::class)
    ->beforeEach(function () {
        artisan('import:project-data');

        $this->sellerAfonsoBeloHorizonte = User::whereEmail('afonso.afancar@magazineaziul.com.br')->first();
        $this->sellerAlceuBeloHorizonte = User::whereEmail('alceu.andreoli@magazineaziul.com.br')->first();
        $this->sellerDaviVitoria = User::whereEmail('davi@magazineaziul.com.br')->first();
        $this->sellerBrenoCampoGrande = User::whereEmail('breno@magazineaziul.com.br')->first();
        $this->sellerAugustoFlorianopolis = User::whereEmail('augusto@magazineaziul.com.br')->first();

        Sale::factory()->count(1)->create([
            'seller_id' => $this->sellerAfonsoBeloHorizonte->id,
            'branch_office_id' => $this->sellerAfonsoBeloHorizonte->getBranchOffices()->first()->id,
        ]);

        Sale::factory()->count(2)->create([
            'seller_id' => $this->sellerAlceuBeloHorizonte->id,
            'branch_office_id' => $this->sellerAlceuBeloHorizonte->getBranchOffices()->first()->id,
        ]);

        Sale::factory()->count(3)->create([
            'seller_id' => $this->sellerDaviVitoria->id,
            'branch_office_id' => $this->sellerDaviVitoria->getBranchOffices()->first()->id,
        ]);

        Sale::factory()->count(6)->create([
            'seller_id' => $this->sellerBrenoCampoGrande->id,
            'branch_office_id' => $this->sellerBrenoCampoGrande->getBranchOffices()->first()->id,
        ]);

        Sale::factory()->count(7)->create([
            'seller_id' => $this->sellerAugustoFlorianopolis->id,
            'branch_office_id' => $this->sellerAugustoFlorianopolis->getBranchOffices()->first()->id,
        ]);
    });


test('By General Director', function () {
    $user = User::whereEmail('pele@magazineaziul.com.br')->first();
    actingAs($user)
        ->getJson(route('api.sales.index'))
        ->assertSuccessful()
        ->assertJson(function (AssertableJson $json) {
            $json->where('total', 19)
                ->etc();
        });
});

test('By Regional Director -> Sudeste', function () {
    $user = User::whereEmail('abel.ferreira@magazineaziul.com.br')->first();
    actingAs($user)
        ->getJson(route('api.sales.index'))
        ->assertSuccessful()
        ->assertJson(function (AssertableJson $json) {
            $json->where('total', 6)
                ->etc();
        });
});

test('By Manager -> Florianopolis', function () {
    $user = User::whereEmail('roberto.firmino@magazineaziul.com.br')->first();
    actingAs($user)
        ->getJson(route('api.sales.index'))
        ->assertSuccessful()
        ->assertJson(function (AssertableJson $json) {
            $json->where('total', 7)
                ->etc();
        });
});

test('By Seller -> Alceu Belo Horizonte', function () {
    $user = User::whereEmail('alceu.andreoli@magazineaziul.com.br')->first();
    actingAs($user)
        ->getJson(route('api.sales.index'))
        ->assertSuccessful()
        ->assertJson(function (AssertableJson $json) {
            $json->where('total', 2)
                ->etc();
        });
});

