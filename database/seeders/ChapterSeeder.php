<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chapters')->insert([
            [
                'name' => "Intro",
                'course_id' => 1
            ],
            [
                'name' => "HTML",
                'course_id' => 1
            ],
        ]);
    }
}
