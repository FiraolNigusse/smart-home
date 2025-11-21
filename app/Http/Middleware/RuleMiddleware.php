<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RuleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, $next)
{
    $user = auth()->user();
    $hour = now()->hour;

    if ($user->role->name == 'Guest') {
        if ($hour < 7 || $hour > 21) {
            ActivityLog::create([
                'user_id'=>$user->id,
                'device_id'=>$request->device->id ?? null,
                'action'=>'rule-check',
                'result'=>'denied'
            ]);
            return back()->with('error', 'Access restricted by rules');
        }
    }

    return $next($request);
}

}
