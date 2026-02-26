<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Dictionaries\LegalType;
use App\Models\Entities\Address\Country;
use Illuminate\Support\Facades\Auth;

final class OnboardingController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $countries = Country::all();
        $legalTypes = LegalType::all();
        $authUser = Auth::user();
        $user = $authUser ? ['login' => $authUser->email ?? $authUser->phone] : null;

        return view('onboarding.create-company', compact('countries', 'legalTypes', 'user'));
    }
}
