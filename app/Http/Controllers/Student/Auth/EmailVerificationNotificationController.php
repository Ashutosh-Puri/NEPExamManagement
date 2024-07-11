<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            if($request->user('student')->is_profile_complete===1)
            {
                return redirect()->intended(RouteServiceProvider::STUDENTHOME);
            }else
            {              
                return redirect()->route('student.profile');
            }
        }

        $request->user('student')->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
