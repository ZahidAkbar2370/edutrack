<?php

namespace App\Http\Controllers;

use App\Models\Student;

class StudentCardController extends Controller
{
    public function select($id)
    {
        $student = Student::with('school', 'schoolClass', 'section', 'parent')->findOrFail($id);
        $cardData = $this->prepareCardData($student);

        return view('student.card.select', compact('student', 'cardData'));
    }

    private function prepareCardData(Student $student): array
    {
        $school = $student->school;
        $className = $student->schoolClass->class_name ?? '—';
        $sectionName = $student->section->section_name ?? '—';

        return [
            'student' => $student,
            'school' => $school,
            'photoUrl' => $student->photo_url,
            'schoolName' => $school->school_name ?? 'School Name',
            'principalName' => $school->priciple_name ?? 'Principal',
            'studentName' => $student->student_name,
            'studentEmail' => $student->student_email ?? '—',
            'studentPhone' => $student->student_phone_no ?? ($student->parent->parent_phone_no ?? '—'),
            'className' => $className,
            'sectionName' => $sectionName,
            'cardId' => $student->student_roll_number ?? substr($student->id, 0, 8),
            'fatherName' => $student->parent->parent_name ?? '—',
            'fatherPhone' => $student->parent->parent_phone_no ?? '—',
        ];
    }
}
