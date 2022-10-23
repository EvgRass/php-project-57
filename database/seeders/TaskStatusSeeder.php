<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['name' => __('messages.status_new'), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => __('messages.status_in_work'), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => __('messages.on_testing'), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => __('messages.completed'), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];
        DB::table('task_statuses')->insert($statuses);
    }
}
