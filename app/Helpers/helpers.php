<?php

use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\WhatsappDevice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Class Helper Functions
*/

// login school all (active and inactive) classes
if(!function_exists('loginSchoolAllClasses')){
    function loginSchoolAllClasses(){
        $schoolClasses = SchoolClass::where('school_id', Auth::user()->school_id)->get();

        return $schoolClasses;
    }
}

// login school active classes
if(!function_exists('loginSchoolActiveClasses')){
    function loginSchoolActiveClasses(){
        $schoolClasses = SchoolClass::where('school_id', Auth::user()->school_id)->where('publication_status', 'active')->get();

        return $schoolClasses;
    }
}




/**
 * Section Helper Functions
*/


// login school all (active and inactive) sections
if(!function_exists('loginSchoolAllSections')){
    function loginSchoolAllSections(){
        $sections = Section::where('school_id', Auth::user()->school_id)->get();

        return $sections;
    }
}

// login school active sections
if(!function_exists('loginSchoolActiveSections')){
    function loginSchoolActiveSections(){
        $sections = Section::where('school_id', Auth::user()->school_id)->where('publication_status', 'active')->get();

        return $sections;
    }
}

// login school active sections by class_id
if(!function_exists('loginSchoolActiveSectionsByClassId')){
    function loginSchoolActiveSectionsByClassId($classId){
        $sections = Section::where('school_id', Auth::user()->school_id)->where('publication_status', 'active')->where('class_id', $classId)->get();

        return $sections;
    }
}


// send whatsapp message
if(!function_exists('sendWhatsappMessage')){
    function sendWhatsappMessage($sender, $toNumber, $message){
        $sendMessageUrl = env('WACHAT_API_URL').'/send-message';
        $apiKey = env('WACHAT_API_KEY');

        $sendMessageResponse = Http::post($sendMessageUrl, [
            'api_key' => $apiKey,
            'sender' => $sender,
            'number' => $toNumber,
            'message' => $message,
        ]);

        return $sendMessageResponse;
    }
}
