<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $dates = collect(range(0, 6))->map(fn ($daysAgo) => Carbon::today()->subDays($daysAgo)->format('Y-m-d'));

        $sections = Section::orderBy('section_name')->get();

        foreach ($sections as $section) {
            $students = Student::where('section_id', $section->id)->get();

            if ($students->isEmpty()) {
                continue;
            }

            foreach ($dates as $date) {
                $sessionExists = Attendance::where('school_id', $section->school_id)
                    ->where('class_id', $section->class_id)
                    ->where('section_id', $section->id)
                    ->whereDate('attendance_date', $date)
                    ->exists();

                if ($sessionExists) {
                    continue;
                }

                foreach ($students as $student) {
                    Attendance::factory()->create([
                        'school_id' => $section->school_id,
                        'class_id' => $section->class_id,
                        'section_id' => $section->id,
                        'student_id' => $student->id,
                        'attendance_date' => $date,
                    ]);
                }
            }
        }
    }
}
