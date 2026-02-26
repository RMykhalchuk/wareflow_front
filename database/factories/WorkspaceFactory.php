<?php

namespace Database\Factories;

use App\Models\Entities\System\Workspace;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entities\System\Workspace>
 */
class WorkspaceFactory extends Factory
{
    protected $model = Workspace::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'user_id' => User::factory(),
            'avatar_color' => $this->faker->safeHexColor(),
            'employees_count' => $this->faker->numberBetween(10, 100)
        ];
    }
}
