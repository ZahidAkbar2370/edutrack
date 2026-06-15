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
            'school_email' => ['required', 'string', 'email', 'max:255', 'unique:schools'],
            'school_phone_no' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],

            'priciple_name' => ['required', 'string', 'max:255'],
            'priciple_phone_no' => ['nullable', 'string', 'max:255'],
            'priciple_email' => ['nullable', 'string', 'email', 'max:255', 'unique:schools'],
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

            'priciple_name' => $data['priciple_name'],
            'priciple_phone_no' => $data['priciple_phone_no'],
            'priciple_email' => $data['priciple_email'],
        ]);

        $user = User::create([
            'school_id' => $school->id,
            'membership_id' => $membership->id,
            'name' => $data['school_name'],
            'email' => $data['school_email'],
            'password' => Hash::make("12345678"),
            'email_verified_at' => Carbon::now(),
            'role' => 'school-admin',
            'membership_expiry_date' => Carbon::now()->addDays(7),
        ]);

        $classes = ['LKG', 'UKG', 'Nursery', 'Play Group', 'Prep', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine Computer', 'Nine Biology', 'Nine Arts' , 'Ten Computer', 'Ten Biology', 'Ten Arts' , 'Eleven ICS', 'Eleven Medical', 'Eleven Non-Medical ', 'Eleven FA', 'Eleven Diploma', 'Twelve ICS', 'Twelve Biology', 'Twelve Non-Medical', 'Twelve FA', 'Twelve Diploma'];
        foreach($classes as $class){
            $publication_status = 'active';

            if($class == 'Nine Computer' || $class == 'Nine Biology' || $class == 'Nine Arts' || $class == 'Ten Computer' || $class == 'Ten Biology' || $class == 'Ten Arts' || $class == 'Eleven ICS' || $class == 'Eleven Medical' || $class == 'Eleven Non-Medical' || $class == 'Eleven FA' || $class == 'Eleven Diploma' || $class == 'Twelve ICS' || $class == 'Twelve Biology' || $class == 'Twelve Non-Medical' || $class == 'Twelve FA' || $class == 'Twelve Diploma'){
                $publication_status = 'inactive';
            }

            $class = SchoolClass::create([
                'school_id' => $school->id,
                'class_name' => $class,
                'publication_status' => $publication_status,
            ]);

            $sections = ['A', 'B', 'C', 'D'];
            
            foreach($sections as $section){
                $publication_status = 'active';

                if($section == 'B' || $section == 'C' || $section == 'D' ){
                    $publication_status = 'inactive';
                }

                Section::create([
                    'school_id' => $school->id,
                    'class_id' => $class->id,
                    'section_name' => $section,
                    'publication_status' => $publication_status,
                ]);
            }
        }

        $subjects = [
            'English','Urdu','Mathematics','General Mathematics','Islamiyat','Pakistan Studies','General Knowledge','General Science','Social Studies','Physics','Chemistry','Biology','Computer','Computer Science','Education','Economics','Principles of Accounting','Principles of Commerce','Business Mathematics','Drawing',
            'Social Studies','General Science','Computer Science','History','Geography','Economics','Political Science','Philosophy','Religion','Art','Music','Dance','Sports','Other'
        ];

        foreach($subjects as $subject){
            $publication_status = 'active';

            if($subject == 'English' || $subject == 'Urdu' || $subject == 'Mathematics' || $subject == 'Islamiyat' || $subject == 'Pakistan Studies' ||  $subject == 'General Science' || $subject == 'Social Studies' || $subject == 'Physics' || $subject == 'Chemistry' || $subject == 'Biology' || $subject == 'Computer' || $subject == 'Computer Science' || $subject == 'Education' || $subject == 'Economics' || $subject == 'Principles of Accounting' || $subject == 'Principles of Commerce' || $subject == 'Business Mathematics')
            {
                $publication_status = 'inactive';
            }

            Subject::create([
                'school_id' => $school->id,
                'subject_name' => $subject,
                'publication_status' => $publication_status,
            ]);
        }

        // $user->sendEmailVerificationNotification();

        DB::commit();

        return $user;
    }
}
