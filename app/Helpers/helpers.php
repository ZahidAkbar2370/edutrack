<?php

use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;


/**
 * Class Helper Functions
*/

// login school all (active and inactive) classes
if(!function_exists('loginSchoolAllClasses')){
    function loginSchoolAllClasses(){
        $schoolClasses = SchoolClass::where('school_id', Auth::user()->school_id)->orderBy('created_at', 'desc')->get();

        return $schoolClasses;
    }
}

// login school active classes
if(!function_exists('loginSchoolActiveClasses')){
    function loginSchoolActiveClasses(){
        $schoolClasses = SchoolClass::where('school_id', Auth::user()->school_id)->where('publication_status', 'active')->orderBy('created_at', 'desc')->get();

        return $schoolClasses;
    }
}




/**
 * Section Helper Functions
*/


// login school all (active and inactive) sections
if(!function_exists('loginSchoolAllSections')){
    function loginSchoolAllSections(){
        $sections = Section::where('school_id', Auth::user()->school_id)->orderBy('created_at', 'desc')->get();

        return $sections;
    }
}

// login school active sections
if(!function_exists('loginSchoolActiveSections')){
    function loginSchoolActiveSections(){
        $sections = Section::where('school_id', Auth::user()->school_id)->where('publication_status', 'active')->orderBy('created_at', 'desc')->get();

        return $sections;
    }
}

// login school active sections by class_id
if(!function_exists('loginSchoolActiveSectionsByClassId')){
    function loginSchoolActiveSectionsByClassId($classId){
        $sections = Section::where('school_id', Auth::user()->school_id)->where('publication_status', 'active')->where('class_id', $classId)->orderBy('created_at', 'desc')->get();

        return $sections;
    }
}