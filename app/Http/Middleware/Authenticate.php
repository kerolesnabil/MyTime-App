<?php

namespace App\Http\Middleware;

use App\Helpers\ResponsesHelper;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            return ResponsesHelper::returnError(400, __('auth.unauthorized'));
        }
        else
        {
            return route('login');
        }

    }
}
