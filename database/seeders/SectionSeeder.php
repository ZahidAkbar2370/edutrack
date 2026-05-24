<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        // [class_name => section_name]
        $sections = [
            ['One', 'One A'],
            ['One', 'One B'],
            ['Two', 'Two A'],
            ['Three', 'Three A'],
            ['Three', 'Three B'],
            ['Three', 'Three C'],
        ];

        $schools = School::orderBy('created_at')->get();

        foreach ($schools as $school) {
            foreach ($sections as [$className, $sectionName]) {
                $schoolClass = SchoolClass::where('school_id', $school->id)
                    ->where('class_name', $className)
                    ->first();

                if (! $schoolClass) {
                    continue;
                }

                Section::updateOrCreate(
                    [
                        'school_id' => $school->id,
                        'class_id' => $schoolClass->id,
                        'section_name' => $sectionName,
                    ],
                    [
                        'school_id' => $school->id,
                        'class_id' => $schoolClass->id,
                        'section_name' => $sectionName,
                    ]
                );
            }
        }
    }
}
