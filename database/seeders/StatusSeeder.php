<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert(
            [
                [
                    'name' => 'Pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Processing',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Completed',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Cancelled',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
            );
    }
}
