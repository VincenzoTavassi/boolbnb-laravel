<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bronze_plan = new Plan;
        $bronze_plan->title = 'Bronze';
        $bronze_plan->price = 2.99;
        $bronze_plan->length = 24;
        $bronze_plan->save();

        $silver_plan = new Plan;
        $silver_plan->title = 'Silver';
        $silver_plan->price = 5.99;
        $silver_plan->length = 72;
        $silver_plan->save();

        $gold_plan = new Plan;
        $gold_plan->title = 'Gold';
        $gold_plan->price = 9.99;
        $gold_plan->length = 144;
        $gold_plan->save();
    }
}
