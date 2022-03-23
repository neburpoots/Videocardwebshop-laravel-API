<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $userId = DB::table('users')->pluck('id');

        $order1 = Order::create([
            'user_id' => $faker->randomElement($userId),
            'order_date' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $order2 = Order::create([
            'user_id' => $faker->randomElement($userId),
            'order_date' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $order3 = Order::create([
            'user_id' => $faker->randomElement($userId),
            'order_date' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);


        // SEEDS PIVOT TABLE

        $order1->products()->sync([1 => ['quantity' => 3], 2 => ['quantity' => 2]]); 
        $order2->products()->sync([2 => ['quantity' => 1], 3 => ['quantity' => 3]]); // array of products for pivot table
        $order3->products()->sync([1 => ['quantity' => 5], 3 => ['quantity' => 1]]); // array of products for pivot table
    }
}
