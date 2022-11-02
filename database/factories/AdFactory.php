<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ad>
 */
class AdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //ad factory
            'title' => $this->faker->sentence(3),
            'description' => fake()->paragraph(rand(3,7)),
            'price' => fake()->randomFloat(2, 0, 1000),
            'photo' => $this->faker->randomElement([
              '240b4011-41b4-44a6-978e-dcbc5a9ec722.jpg',
              '2557b6ab-ca9a-424f-9cbe-cbf7ca14bdcc.jpg',
              '305eb55f-c0ed-46ad-ad38-19ab9e333637.jpg',
              '3a1f540e-f749-4bcf-b86f-ff205ae706fe.jpg',
              '50986090-f375-4fef-8ae5-6b2aa15dc688.jpg',
              '5ac031ed-ae88-481f-8509-928689f78bf9.jpg',
              '69f0d011-d1bb-4c61-9f1b-dd0dec114842.jpg',
              '7a7cfb02-76c2-4e27-9476-f0ed72759c35.jpg',
              '9230eafe-cad4-4e62-b50b-5477118e5128.jpg',
              'b7daa210-18e1-48cf-9738-8f578b6e67c1.jpg',
              'f2fdc4e7-56a8-4832-973c-dafc79fe6922.jpg',
            ]),
            'url' => strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', trim(fake()->sentence(3), '-'))) . '-' . explode('-', Uuid::uuid4())[4],

        ];
    }
}
