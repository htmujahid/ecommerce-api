<?php

namespace Database\Factories;

use App\Models\Seller;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\MediaLibrary\MediaCollections\Exceptions\UnreachableUrl;

class SellerFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Seller::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'birthday' => $this->faker->dateTimeBetween('-35 years', '-18 years'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'created_at' => $this->faker->dateTimeBetween('-1 year', '-6 month'),
            'updated_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }

    public function configure(): SellerFactory
    {
        return $this->afterCreating(function (Seller $seller) {
            try {
                $seller
                    ->addMediaFromUrl(DatabaseSeeder::IMAGE_URL)
                    ->toMediaCollection('seller-images');
            } catch (UnreachableUrl $exception) {
                return;
            }
        });
    }
}
