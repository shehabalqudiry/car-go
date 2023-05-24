<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

use function App\Helpers\returnError;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if(!$request->expectsJson()) {
            // dd($request);
            if($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }
            // return returnError("LOGIN00", __('Unauthorized'));
            return route('front.auth.login');
        }
        return null;
    }
}
