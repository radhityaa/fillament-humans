<?php

namespace Database\Factories;

use App\Enums\EmployeeStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Departement;
use App\Models\Employee;
use App\Models\Position;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'departement_id' => rand(1, 5),
            'position_id' => rand(1, 5),
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'joined' => $this->faker->date(),
            'status' => collect(EmployeeStatus::class),
        ];
    }
}
