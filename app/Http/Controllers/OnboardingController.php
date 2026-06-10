<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function interest()
    {
        return view('onboarding.interest');
    }

    public function storeInterest(Request $request)
    {
        $request->validate([
            'interest' => 'required|string|max:100',
        ]);

        Auth::user()->update(['interest' => $request->interest]);

        return redirect()->route('onboarding.focus');
    }

    public function focus()
    {
        $user = Auth::user();
        if (!$user->interest) {
            return redirect()->route('onboarding.interest');
        }
        return view('onboarding.focus', ['interest' => $user->interest]);
    }

    public function storeFocus(Request $request)
    {
        $request->validate([
            'focus' => 'required|string|max:100',
        ]);

        Auth::user()->update([
            'focus' => $request->focus,
            'onboarding_completed' => true,
        ]);

        return redirect()->route('onboarding.welcome');
    }

    public function welcome()
    {
        $user = Auth::user();
        if (!$user->onboarding_completed) {
            return redirect()->route('onboarding.interest');
        }
        return view('onboarding.welcome', ['user' => $user]);
    }
}
