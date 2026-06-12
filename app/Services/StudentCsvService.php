<?php

namespace App\Services;

use App\Models\ParentModel;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class StudentCsvService
{
    public const HEADERS = [
        'Student Name',
        'Student Email',
        'Student Phone Number',
        'Class Name',
        'Section Name',
        'Admission Date',
        'Parent Name',
        'Parent Phone Number',
        'Parent Email',
        'Address',
        'Monthly Fee',
    ];

    /** Build student query with same filters as the list page */
    public function filteredQuery($schoolId, Request $request)
    {
        return Student::with('schoolClass', 'section', 'parent')
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->when($request->filled('class_id'), fn ($query) => $query->where('class_id', $request->class_id))
            ->when($request->filled('section_id'), fn ($query) => $query->where('section_id', $request->section_id))
            ->when($request->filled('name_roll_number'), function ($query) use ($request) {
                $name = '%' . $request->name . '%';
                $query->where(function ($q) use ($name) {
                    $q->where('student_name', 'like', $name)
                        ->orWhere('student_roll_number', 'like', $name);
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->orderBy('student_name');
    }

    /** One CSV row for a student */
    public function rowFromStudent(Student $student): array
    {
        return [
            $student->student_name,
            $student->student_email ?? '',
            $student->student_phone_no ?? '',
            $student->schoolClass->class_name ?? '',
            $student->section->section_name ?? '',
            $student->student_admission_date
                ? Carbon::parse($student->student_admission_date)->format('Y-m-d')
                : '',
            $student->parent->parent_name ?? '',
            $student->parent->parent_phone_no ?? '',
            $student->parent->parent_email ?? '',
            $student->parent->parent_address ?? '',
            $student->student_per_month_fee ?? '0',
        ];
    }

    /**
     * Import CSV rows for one school. Returns ['imported' => int, 'errors' => string[]].
     */
    public function import(UploadedFile $file, string $schoolId): array
    {
        $imported = 0;
        $errors = [];

        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            return ['imported' => 0, 'errors' => ['Could not read the CSV file.']];
        }

        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);

            return ['imported' => 0, 'errors' => ['CSV file is empty.']];
        }

        $firstLine = $this->stripBom($firstLine);
        $header = str_getcsv($firstLine);
        $columnMap = $this->mapHeaders($header);

        if ($columnMap === null) {
            fclose($handle);

            return ['imported' => 0, 'errors' => ['Invalid CSV headers. Download the sample template and use the same column names.']];
        }

        $classes = SchoolClass::where('school_id', $schoolId)->get()->keyBy(fn ($c) => strtolower(trim($c->class_name)));
        $sections = Section::where('school_id', $schoolId)->with('schoolClass')->get();

        $rowNum = 1;
        while (($data = fgetcsv($handle)) !== false) {
            $rowNum++;
            if ($this->isEmptyRow($data)) {
                continue;
            }

            $row = $this->rowFromCsvData($data, $columnMap);

            $lineErrors = $this->validateImportRow($row, $rowNum);
            if ($lineErrors) {
                $errors = array_merge($errors, $lineErrors);
                continue;
            }

            $classKey = strtolower(trim($row['class_name']));
            $schoolClass = $classes->get($classKey);
            if (! $schoolClass) {
                $errors[] = "Row {$rowNum}: Class \"{$row['class_name']}\" not found.";
                continue;
            }

            $section = $sections->first(function ($sec) use ($row, $schoolClass) {
                return strtolower(trim($sec->section_name)) === strtolower(trim($row['section_name']))
                    && $sec->class_id === $schoolClass->id;
            });

            if (! $section) {
                $errors[] = "Row {$rowNum}: Section \"{$row['section_name']}\" not found in class \"{$row['class_name']}\".";
                continue;
            }

            $status = strtolower(trim($row['status'] ?? ''));
            if ($status === '') {
                $status = Student::STATUS_ACTIVE;
            } elseif (! in_array($status, Student::STATUSES, true)) {
                $errors[] = "Row {$rowNum}: status must be one of: " . implode(', ', Student::STATUSES) . '.';
                continue;
            }

            try {
                DB::transaction(function () use ($row, $schoolId, $schoolClass, $section, $status) {
                    $student = Student::create([
                        'school_id' => $schoolId,
                        'class_id' => $schoolClass->id,
                        'section_id' => $section->id,
                        'student_name' => $row['student_name'],
                        'student_email' => $row['student_email'] ?: null,
                        'student_phone_no' => $row['student_phone_no'] ?: null,
                        'student_photo' => Student::DEFAULT_PHOTO,
                        'student_roll_number' => $row['student_roll_number'] ?: null,
                        'student_admission_date' => $row['student_admission_date'] ?: null,
                        'status' => $status,
                    ]);

                    ParentModel::create([
                        'school_id' => $schoolId,
                        'student_id' => $student->id,
                        'parent_name' => $row['parent_name'],
                        'parent_phone_no' => $row['parent_phone_no'],
                        'parent_email' => $row['parent_email'] ?: null,
                        'parent_address' => $row['parent_address'] ?: null,
                    ]);
                });
                $imported++;
            } catch (\Throwable $e) {
                $errors[] = "Row {$rowNum}: Could not save — " . $e->getMessage();
            }
        }

        fclose($handle);

        return ['imported' => $imported, 'errors' => $errors];
    }

    private function stripBom(string $line): string
    {
        return preg_replace('/^\xEF\xBB\xBF/', '', $line) ?? $line;
    }

    private function mapHeaders(array $header): ?array
    {
        $normalized = array_map(fn ($h) => strtolower(trim(str_replace([' ', '-'], '_', $h ?? ''))), $header);
        $map = [];

        $required = array_values(array_diff(self::HEADERS, self::OPTIONAL_HEADERS));

        foreach ($required as $column) {
            $index = array_search($column, $normalized, true);
            if ($index === false) {
                return null;
            }
            $map[$column] = $index;
        }

        foreach (self::OPTIONAL_HEADERS as $column) {
            $index = array_search($column, $normalized, true);
            if ($index !== false) {
                $map[$column] = $index;
            }
        }

        return $map;
    }

    private function rowFromCsvData(array $data, array $columnMap): array
    {
        $row = [];
        foreach (self::HEADERS as $key) {
            $row[$key] = isset($columnMap[$key])
                ? trim($data[$columnMap[$key]] ?? '')
                : '';
        }

        return $row;
    }

    private function isEmptyRow(array $data): bool
    {
        return trim(implode('', $data)) === '';
    }

    private function validateImportRow(array $row, int $rowNum): array
    {
        $errors = [];

        if ($row['student_name'] === '') {
            $errors[] = "Row {$rowNum}: student_name is required.";
        }
        if ($row['class_name'] === '') {
            $errors[] = "Row {$rowNum}: class_name is required.";
        }
        if ($row['section_name'] === '') {
            $errors[] = "Row {$rowNum}: section_name is required.";
        }
        if ($row['parent_name'] === '') {
            $errors[] = "Row {$rowNum}: parent_name is required.";
        }
        if ($row['parent_phone_no'] === '') {
            $errors[] = "Row {$rowNum}: parent_phone_no is required.";
        }

        if ($row['student_email'] !== '' && ! filter_var($row['student_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Row {$rowNum}: student_email is invalid.";
        }
        if ($row['parent_email'] !== '' && ! filter_var($row['parent_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Row {$rowNum}: parent_email is invalid.";
        }

        if ($row['student_admission_date'] !== '') {
            try {
                Carbon::parse($row['student_admission_date']);
            } catch (\Throwable) {
                $errors[] = "Row {$rowNum}: student_admission_date must be a valid date (e.g. 2025-01-15).";
            }
        }

        return $errors;
    }
}
