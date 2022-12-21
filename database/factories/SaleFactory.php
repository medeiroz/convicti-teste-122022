<?php

namespace Database\Factories;

use App\Models\BranchOffice;
use App\Models\User;
use App\Modules\Import\ImportProjectData\Enum\RoleEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'seller_id' => fn () => User::whereRole(RoleEnum::SELLER)->inRandomOrder()->first()->id,
            'branch_office_id' => fn () => BranchOffice::inRandomOrder()->first()->id,
            'roaming_branch_office_id' => function () {
                if (fake()->boolean) {
                    return BranchOffice::inRandomOrder()->first()->id;
                }
                return null;
            },
            'location' => function () {
                return new Point(fake()->latitude, fake()->longitude);
            },
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 2, 200),
            'sold_at' => now(),
        ];
    }
}
