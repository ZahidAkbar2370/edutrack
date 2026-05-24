<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $sections = Section::orderBy('section_name')->get();

        foreach ($sections as $section) {
            $count = rand(1, 20);

            Student::factory()
                ->count($count)
                ->create([
                    'school_id' => $section->school_id,
                    'class_id' => $section->class_id,
                    'section_id' => $section->id,
                ]);
        }
    }
}
