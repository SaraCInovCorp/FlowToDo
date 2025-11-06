<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ActivityLog;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'log_name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'subject_type' => 'App\Models\Task', 
            'subject_id' => 1, 
            'causer_type' => User::class,
            'causer_id' => User::factory(), 
            'properties' => ['attributes' => ['example_field' => 'example_value']],
            'event' => $this->faker->randomElement(['created', 'updated', 'deleted']),
            'batch_uuid' => null,
        ];
    }
}
