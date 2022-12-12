<?php

namespace Database\Seeders;

use App\Models\Users;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;


class UsersSeeder extends Seeder
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
        Users::truncate();
        Schema::enableForeignKeyConstraints();
        // $faker = Faker\Factory::create();
        foreach (range(1, 10) as $index) {
            $product = new Users();
            $product->name = $this->faker->name;
            $product->email = $this->faker->email;
            $product->password=Hash::make('123456');
            $product->save();
        }
    }
}
