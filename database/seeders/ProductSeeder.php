<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Product::truncate();
        Schema::enableForeignKeyConstraints();
        // $faker = Faker\Factory::create();
        foreach (range(1, 100) as $index) {
            $product = new Product();
            $product->name = $this->faker->name;
            $product->price = $this->faker->randomNumber(2).'00';

            $product->save();
        }
    }
}
