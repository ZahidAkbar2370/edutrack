<?php

namespace Database\Seeders;

use App\Models\DailyTest;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DailyTestSeeder extends Seeder
{
    public function run(): void
    {
        $tests = [
            [
                'daily_test_name' => 'Weekly Quiz',
                'subject' => 'Mathematics',
                'teacher_name' => 'Mr. Ahmed Khan',
                'total' => 50,
                'days_ago' => 3,
            ],
            [
                'daily_test_name' => 'Weekly Quiz',
                'subject' => 'English',
                'teacher_name' => 'Ms. Sara Ali',
                'total' => 40,
                'days_ago' => 5,
            ],
            [
                'daily_test_name' => 'Chapter Test',
                'subject' => 'Science',
                'teacher_name' => 'Mr. Hassan Raza',
                'total' => 30,
                'days_ago' => 7,
            ],
        ];

        $sections = Section::orderBy('section_name')->get();

        foreach ($sections as $section) {
            $students = Student::where('section_id', $section->id)->get();

            if ($students->isEmpty()) {
                continue;
            }

            foreach ($tests as $test) {
                $testDate = Carbon::today()->subDays($test['days_ago'])->format('Y-m-d');

                $teacher = Teacher::firstOrCreate(
                    [
                        'school_id' => $section->school_id,
                        'teacher_name' => $test['teacher_name'],
                    ],
                    [
                        'school_id' => $section->school_id,
                        'teacher_name' => $test['teacher_name'],
                    ]
                );

                $exists = DailyTest::where('school_id', $section->school_id)
                    ->where('class_id', $section->class_id)
                    ->where('section_id', $section->id)
                    ->whereDate('daily_test_date', $testDate)
                    ->where('daily_test_name', $test['daily_test_name'])
                    ->where('teacher_id', $teacher->id)
                    ->where('subject', $test['subject'])
                    ->exists();

                if ($exists) {
                    continue;
                }

                $total = $test['total'];

                foreach ($students as $student) {
                    $obtained = rand(0, $total);
                    $percentage = $total > 0 ? round(($obtained / $total) * 100, 2) : 0;

                    DailyTest::factory()->create([
                        'school_id' => $section->school_id,
                        'class_id' => $section->class_id,
                        'section_id' => $section->id,
                        'student_id' => $student->id,
                        'teacher_id' => $teacher->id,
                        'daily_test_date' => $testDate,
                        'daily_test_name' => $test['daily_test_name'],
                        'subject' => $test['subject'],
                        'daily_test_obtained' => $obtained,
                        'daily_test_total' => $total,
                        'daily_test_percentage' => $percentage,
                    ]);
                }
            }
        }
    }
}
