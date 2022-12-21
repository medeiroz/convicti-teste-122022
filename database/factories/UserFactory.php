<?php

namespace Database\Factories;

use App\Models\BranchOffice;
use App\Models\User;
use App\Modules\Import\ImportProjectData\Enum\RoleEnum;
use App\Repositories\BranchOfficeRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
    public function seller()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => RoleEnum::SELLER->value,
            ];
        });
    }

    public function generalDirector()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => RoleEnum::GENERAL_DIRECTOR->value,
            ];
        });
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            if (RoleEnum::tryFrom($user->role) === RoleEnum::SELLER) {
                $branchOfficeId = BranchOffice::inRandomOrder()->first()->id;

                /** @var BranchOfficeRepository $branchOfficeRepository */
                $branchOfficeRepository = app(BranchOfficeRepository::class);
                $branchOfficeRepository->attachSeller($branchOfficeId, $user->id);
            }
        });
    }
}
