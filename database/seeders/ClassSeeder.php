<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $classNames = [
            'One',
            'Two',
            'Three',
            'Four',
            'Five',
            'Six',
            'Seven',
            'Eight',
            'Nine',
            'Ten',
        ];

        $schools = School::orderBy('created_at')->get();

        foreach ($schools as $school) {
            foreach ($classNames as $className) {
                SchoolClass::updateOrCreate(
                    [
                        'school_id' => $school->id,
                        'class_name' => $className,
                    ],
                    [
                        'school_id' => $school->id,
                        'class_name' => $className,
                    ]
                );
            }
        }
    }
}
