<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Faculty::create([
            'name' => 'Management',
            'description' => 'Management based courses'
        ]);
        Faculty::create([
            'name' => 'Health Science',
            'description' => 'Health based courses'
        ]);
    }
}
