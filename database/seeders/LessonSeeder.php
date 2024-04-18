<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lessons')->insert([
            [
                'name' => "Belajar HTML untuk pemula - intro",
                'video' => 'kr4882GSwpA',
                'chapter_id' => 1
            ],
            [
                'name' => "1 file html pertama",
                'video' => 'v88LehZU7Wk',
                'chapter_id' => 2
            ],
            [
                'name' => "2 Berkenalan dengan tag html",
                'video' => '8KvOrvjbN1E',
                'chapter_id' => 2
            ],
        ]);
    }
}
