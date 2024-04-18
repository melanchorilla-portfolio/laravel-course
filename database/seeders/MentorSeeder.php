<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MentorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mentors')->insert([
            'name' => "Sekolah Koding",
            'profile' => 'https://images.pexels.com/photos/1714208/pexels-photo-1714208.jpeg',
            'email' => "sekolahkoding@email.com",
            'profession' => 'Programmer'
        ]);
    }
}
