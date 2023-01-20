<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faculties = Faculty::get();

        foreach ($faculties as $faculty) {
            if ($faculty->name == 'Management') {
                $programs = [
                    'BBA',
                    'BCIS',
                    'BHCM',
                ];

                foreach ($programs as $program) {
                    $program =  Program::create([
                        'faculty_id' => $faculty->id,
                        'name' => $program
                    ]);
                }
            } elseif ($faculty->name == 'Health Science') {
                $programs = [
                    'BPH',
                    'BPHARM',
                    'BMLT',
                ];

                foreach ($programs as $program) {
                    $program =  Program::create([
                        'faculty_id' => $faculty->id,
                        'name' => $program
                    ]);
                }
            }
        }
    }
}
