<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'school_name' => ['required', 'string', 'max:255'],
            'school_email' => ['required', 'string', 'email', 'max:255'],
            'school_phone_no' => ['required', 'string', 'max:255', 'regex:/^92\d{10}$/'],

            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],

            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        $membership = Membership::first();

        DB::beginTransaction();

        $school = School::create([
            'membership_id' => $membership->id,
            'school_name' => $data['school_name'],
            'school_email' => $data['school_email'],
            'school_phone_no' => $data['school_phone_no'],
            'city' => $data['city'],
            'address' => $data['address'],
        ]);

        $user = User::create([
            'school_id' => $school->id,
            'membership_id' => $membership->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => Carbon::now(),
            'role' => 'school-admin',
            'membership_expiry_date' => Carbon::now()->addDays(7),
        ]);

        $classes = ['Nursery', 'Play Group', 'Prep', 'Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10', 'Class 11', 'Class 12'];
        foreach ($classes as $key => $class) {


            if ($key <= 8) {
                $class = SchoolClass::create([
                    'school_id' => $school->id,
                    'class_name' => $class,
                    'publication_status' => 'active',
                ]);

                $sections = ['A'];

                foreach ($sections as $section) {
                    Section::create([
                        'school_id' => $school->id,
                        'class_id' => $class->id,
                        'section_name' => $section,
                        'publication_status' => 'active',
                    ]);
                }
            }
        }

        $subjects = [
            'English',
            'Urdu',
            'Mathematics',
            'General Mathematics',
            'Islamiyat',
            'Pakistan Studies',
            'General Knowledge',
            'General Science',
            'Social Studies',
            'Physics',
            'Chemistry',
            'Biology',
            'Computer',
            'Computer Science',
            'Education',
            'Economics',
            'Principles of Accounting',
            'Principles of Commerce',
            'Business Mathematics',
            'Drawing',
            'Social Studies',
            'General Science',
            'Computer Science',
            'History',
            'Geography',
            'Economics',
            'Political Science',
            'Philosophy',
            'Religion',
            'Art',
            'Music',
            'Dance',
            'Sports',
            'Other'
        ];

        foreach ($subjects as $subject) {
            Subject::create([
                'school_id' => $school->id,
                'subject_name' => $subject,
                'publication_status' => 'active',
            ]);
        }

        // $user->sendEmailVerificationNotification();

        $superAdminWhatsappAllow = true;
        if ($superAdminWhatsappAllow) {
            $messageBodyToSchoolAdmin = "Aslam o Alaikum, " . $user->name .

                "\n\n Welcome to EduTrack - Smart School Management System" .

                "\n\n *Login Details*" .
                "\n- Email: " . $user->email .
                "\n- Password: " . $data['password'] .

                "\n\n *School Details*" .
                "\n- School Name: " . $school->school_name .
                "\n- School Email: " . $school->school_email .
                "\n- School Phone No: +" . $school->school_phone_no .
                "\n- School City: " . $school->city .
                "\n- School Address: " . $school->address .

                "\n\n *Website:* edutrack.softwebies.com" .
                "\n *Whatsapp:* +92 320 0470584" .
                "\n *Email:* edutrack.softwebies@gmail.com" .

                "\n\n *Best Regard*, \n EduTrack Powered by SoftWebies";

            sendWhatsappMessage(env('WACHAT_DEVICE_NUMBER'), $school->school_phone_no, $messageBodyToSchoolAdmin);
        }

        DB::commit();

        return $user;
    }
}
