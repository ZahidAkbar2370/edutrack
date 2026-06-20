<?php

namespace App\Http\Controllers;

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
