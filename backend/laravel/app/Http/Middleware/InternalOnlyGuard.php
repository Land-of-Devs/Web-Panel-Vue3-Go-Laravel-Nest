<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

abstract class InternalOnlyGuard
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, $next)
    {
        $host = gethostbyaddr($request::ip());
        echo $host;

        if ($host != 'wp_go') {
            throw new HttpException(403, $host);
        }

        return $next($request);
    }
}
