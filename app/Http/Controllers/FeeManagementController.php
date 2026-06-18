<?php

namespace App\Http\Controllers;

use App\Models\MonthlyFee;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeeManagementController extends Controller
{
    private function schoolIdOrRedirect()
    {
        $schoolId = Auth::user()->school_id;

        if (! $schoolId) {
            return null;
        }

        return $schoolId;
    }

    private function feeMonth(Request $request): string
    {
        $month = $request->input('fee_month', now()->format('Y-m'));

        if (! preg_match('/^\d{4}-\d{2}$/', $month)) {
            return now()->format('Y-m');
        }

        return $month;
    }

    private function isFullyPaid(?MonthlyFee $fee): bool
    {
        return $fee !== null && (float) $fee->remaining_amount <= 0;
    }

    private function classStatsForMonth(string $schoolId, string $feeMonth)
    {
        $classes = SchoolClass::where('school_id', $schoolId)
            ->where('publication_status', 'active')
            ->get();

        $studentsByClass = Student::where('school_id', $schoolId)
            ->where('status', 'active')
            ->get(['id', 'class_id'])
            ->groupBy('class_id');

        $allStudentIds = $studentsByClass->flatten(1)->pluck('id');

        $feesByStudent = MonthlyFee::where('school_id', $schoolId)
            ->where('payment_date', $feeMonth)
            ->whereIn('student_id', $allStudentIds)
            ->get()
            ->keyBy('student_id');

        return $classes->map(function (SchoolClass $class) use ($studentsByClass, $feesByStudent) {
            $studentIds = ($studentsByClass->get($class->id) ?? collect())->pluck('id');
            $total = $studentIds->count();

            $paid = $studentIds->filter(function ($studentId) use ($feesByStudent) {
                $fee = $feesByStudent->get($studentId);

                return $this->isFullyPaid($fee);
            })->count();

            return (object) [
                'class' => $class,
                'total_students' => $total,
                'paid_count' => $paid,
                'unpaid_count' => max(0, $total - $paid),
            ];
        });
    }

    public function index(Request $request)
    {
        $schoolId = $this->schoolIdOrRedirect();
        if (! $schoolId) {
            return redirect('home')->with('error', 'No school is assigned to this user.');
        }

        $feeMonth = $this->feeMonth($request);
        $classStats = $this->classStatsForMonth($schoolId, $feeMonth);

        return view('feemanagement.index', compact('classStats', 'feeMonth'));
    }

    public function show(Request $request, string $classId)
    {
        $schoolId = $this->schoolIdOrRedirect();
        if (! $schoolId) {
            return redirect('home')->with('error', 'No school is assigned to this user.');
        }

        $schoolClass = SchoolClass::where('school_id', $schoolId)->findOrFail($classId);
        $feeMonth = $this->feeMonth($request);

        $students = Student::with('section')
            ->where('status', 'active')
            ->where('school_id', $schoolId)
            ->where('class_id', $schoolClass->id)
            ->orderBy('student_name')
            ->get();

        $fees = MonthlyFee::where('school_id', $schoolId)
            ->where('payment_date', $feeMonth)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return view('feemanagement.show', compact('schoolClass', 'students', 'fees', 'feeMonth'));
    }

    public function store(Request $request)
    {
        $schoolId = $this->schoolIdOrRedirect();
        if (! $schoolId) {
            return redirect('home')->with('error', 'No school is assigned to this user.');
        }

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'fee_month' => 'required|date_format:Y-m',
            'students' => 'nullable|array',
            'students.*.paid_amount' => 'nullable|numeric|min:0',
            'students.*.any_fine_amount' => 'nullable|numeric|min:0',
            'students.*.any_discount_amount' => 'nullable|numeric|min:0',
            'students.*.note' => 'nullable|string|max:500',
        ]);

        $feeMonth = $request->fee_month;
        $classId = $request->class_id;

        $schoolClass = SchoolClass::where('school_id', $schoolId)->findOrFail($classId);
        $studentPayload = $request->input('students', []);

        $students = Student::where('status', 'active')
            ->where('school_id', $schoolId)
            ->where('class_id', $schoolClass->id)
            ->whereIn('id', array_keys($studentPayload))
            ->get()
            ->keyBy('id');

        $existingFees = MonthlyFee::where('school_id', $schoolId)
            ->where('payment_date', $feeMonth)
            ->whereIn('student_id', $students->keys())
            ->get()
            ->keyBy('student_id');

        $saved = 0;

        DB::transaction(function () use ($studentPayload, $students, $existingFees, $schoolId, $feeMonth, &$saved) {
            foreach ($studentPayload as $studentId => $data) {
                $student = $students->get($studentId);
                if (! $student) {
                    continue;
                }

                $existing = $existingFees->get($studentId);
                if ($this->isFullyPaid($existing)) {
                    continue;
                }

                $monthly = (float) ($student->student_per_month_fee ?? 0);
                $fine = (float) ($data['any_fine_amount'] ?? 0);
                $discount = (float) ($data['any_discount_amount'] ?? 0);
                $paid = (float) ($data['paid_amount'] ?? 0);

                if ($paid <= 0 && $fine <= 0 && $discount <= 0 && ! $existing) {
                    continue;
                }

                $total = max(0, $monthly + $fine - $discount);
                $remaining = max(0, $total - $paid);

                MonthlyFee::updateOrCreate(
                    [
                        'school_id' => $schoolId,
                        'student_id' => $studentId,
                        'payment_date' => $feeMonth,
                    ],
                    [
                        'user_id' => Auth::id(),
                        'monthly_fee_amount' => (string) $monthly,
                        'any_fine_amount' => (string) $fine,
                        'any_discount_amount' => (string) $discount,
                        'total_amount' => (string) $total,
                        'paid_amount' => (string) $paid,
                        'remaining_amount' => (string) $remaining,
                        'note' => $data['note'] ?? null,
                    ]
                );

                $saved++;
            }
        });

        return redirect('fee-management/show/' . $classId . '?fee_month=' . $feeMonth)
            ->with('success', $saved > 0 ? 'Fee records saved successfully.' : 'No changes were made.');
    }
}
