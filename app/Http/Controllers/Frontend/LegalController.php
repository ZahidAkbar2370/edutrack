<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class LegalController extends Controller
{
    public function privacyPolicy()
    {
        return view('frontend.pages.privacy-policy');
    }

    public function termsAndConditions()
    {
        return view('frontend.pages.terms-and-conditions');
    }
}
