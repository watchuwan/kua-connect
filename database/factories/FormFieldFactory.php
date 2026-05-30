<?php

namespace Database\Factories;

use App\Models\FormField;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormFieldFactory extends Factory
{
    protected $model = FormField::class;

    public function definition(): array
    {
        $types = ['text', 'email', 'tel', 'number', 'textarea', 'select', 'date'];

        return [
            'name' => fake()->unique()->word(),
            'label' => fake()->words(3, true),
            'type' => fake()->randomElement($types),
            'required' => fake()->boolean(),
            'options' => null,
            'placeholder' => fake()->word(),
            'help_text' => fake()->sentence(),
            'order' => fake()->numberBetween(0, 100),
            'validation_rules' => null,
            'active' => true,
        ];
    }
}
