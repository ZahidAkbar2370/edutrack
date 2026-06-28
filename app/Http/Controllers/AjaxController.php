<?php

namespace App\Http\Controllers;

use App\Models\MonthlyFee;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    public function sectionsByClassId($classId)
    {
        $sections = Section::where('school_id', Auth::user()->school_id)->where('publication_status', 'active')->where('class_id', $classId)->orderBy('created_at', 'desc')->get();

        return response()->json($sections);
    }

    public function studentsBySectionId($classId, $sectionId, $attendanceDate)
    {
        // dd($classId, $sectionId);
        $students = Student::where('school_id', Auth::user()->school_id)->where('status', 'active')->where('class_id', $classId)->where('section_id', $sectionId)->where('student_admission_date', '<=', $attendanceDate)->get();

        // dd($classId);

        return response()->json($students);
    }

    public function getStudentFeePaymentRecords($studentId, $paymentMonth)
    {
        $studentFeePaymentRecords = MonthlyFee::where('school_id', Auth::user()->school_id)->where('student_id', $studentId)->where('payment_date', $paymentMonth)->get();

        $student = Student::where('school_id', Auth::user()->school_id)->where('id', $studentId)->first();

        $makeTableData = view('schooladmin.feemanagement.student_fee_payment_record', compact('studentFeePaymentRecords', 'student', 'paymentMonth'))->render();

        return response()->json($makeTableData);
    }

    public function storePayFee(Request $request)
    {
        $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'any_fine_amount' => 'required|numeric|min:0',
            'any_discount_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $totalAmount = $request->total_amount;
        $anyFineAmount = $request->any_fine_amount;
        $anyDiscountAmount = $request->any_discount_amount;
        $paidAmount = $request->paid_amount;
        $note = $request->note;

        MonthlyFee::create([
            'user_id' => Auth::user()->id,
            'school_id' => Auth::user()->school_id,
            'student_id' => $request->student_id,
            'payment_date' => $request->payment_month,
            'monthly_fee_amount' => $totalAmount,
            'any_fine_amount' => $anyFineAmount,
            'any_discount_amount' => $anyDiscountAmount,
            'total_amount' => $totalAmount + $anyFineAmount - $anyDiscountAmount,
            'paid_amount' => $paidAmount,
            'remaining_amount' => ($totalAmount + $anyFineAmount) - $anyDiscountAmount - $paidAmount,
            'note' => $note,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Fee record stored successfully']);
    }
}
