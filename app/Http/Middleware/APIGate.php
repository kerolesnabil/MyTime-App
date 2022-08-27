<?php

namespace App\Http\Middleware;

use App\Helpers\ResponsesHelper;
use Closure;
use Illuminate\Http\Request;


class APIGate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if($request->header('Device-Type')!='android' && $request->header('Device-Type')!='ios'){
            return ResponsesHelper::returnError('400','you must add device-type in header');
        }
        if(!$request->header('App-Version-Id'))
        {
            return ResponsesHelper::returnError('400','you must add app-version-id in header');
        }
        if(!$request->header('Accept-Language'))
        {
            return ResponsesHelper::returnError('400','you must add Accept-Language in header');
        }

        if($request->header('Accept-Language')=='ar' || $request->header('Accept-Language')=='en')
        {
            app()->setlocale($request->header('Accept-Language'));
        }

        return $next($request);
    }
}
