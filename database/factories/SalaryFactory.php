<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Salary;

class SalaryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Salary::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'amount' => $this->faker->numberBetween(-10000, 10000),
            'effective_date' => $this->faker->date(),
        ];
    }
}
