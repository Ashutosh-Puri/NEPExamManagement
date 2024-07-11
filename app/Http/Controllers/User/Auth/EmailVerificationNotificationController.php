<?php

namespace App\Http\Controllers\User\Auth;

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
        if ($request->user('user')->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::USERHOME);
        }

        $request->user('user')->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
