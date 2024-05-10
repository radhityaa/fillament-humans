<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\LeaveRequest;

class LeaveRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LeaveRequest::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'type' => $this->faker->word(),
            'status' => $this->faker->word(),
            'reason' => $this->faker->text(),
        ];
    }
}
