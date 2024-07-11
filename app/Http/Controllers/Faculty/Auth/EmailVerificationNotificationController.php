<?php

namespace App\Http\Controllers\Faculty\Auth;

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
        if ($request->user('faculty')->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::FACULTYHOME);
        }

        $request->user('faculty')->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
