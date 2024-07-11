<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // // if (! $request->expectsJson()) {
        // //     return route('login');
        // //   //  return '/';    // Redirection to home for teacher as well as student those who are not logged in
 
        // //  }
        
        if(!$request->expectsJson()){
             
            if($request->routeIs('faculty.*')){
              
                return   route('faculty.login');
            }
            if($request->routeIs('student.*')){
                return   route('student.login');
            }

            if($request->routeIs('user.*')){
                return   route('user.login');
            }

            return   url('/');
        }

        // if ($request->expectsJson()) {
        //     return null;
        // } else {
        //     session()->flash('alert', ['type' => 'info', 'message' => 'You Need To Login To Access Authorized Resources.']);
        //     return url('/');
        // }
      
    }
}
