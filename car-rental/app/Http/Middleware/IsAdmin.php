<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    { // open the handle method
        if (!auth()->check() || !auth()->user()->is_admin) { // check if they are NOT logged in, OR NOT an admin
            abort(403, 'Unauthorized action.'); // if so, kick them out with a 403 Forbidden error
        } // close the security check block

        return $next($request); // if they ARE an admin, allow them to proceed to the next step (the controller)
    } // close the handle method
}
