<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses')->insert([
            'name' => "HTML Dasar",
            'certificate' => 1,
            'thumbnail' => 'https://i.ytimg.com/vi/kr4882GSwpA/hqdefault.jpg?sqp=-oaymwEcCPYBEIoBSFXyq4qpAw4IARUAAIhCGAFwAcABBg==&rs=AOn4CLDXkaxkWgYcwJGR83x9Hk90wkoTDA',
            'type' => "free",
            'status' => 'published',
            'price' => 0,
            'level' => 'beginner',
            'description' => 'tutorial html bahasa indonesia, belajar apa itu html untuk pemula',
            'mentor_id' => 1
        ]);
    }
}
