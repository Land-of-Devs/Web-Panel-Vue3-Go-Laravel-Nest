<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InternalOnlyGuard
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
        $host = gethostbyaddr($request->ip());

        $host = explode('.',$host)[0];
        if ($host != 'wp_go') {
            throw new HttpException(403, $host);
        }

        return $next($request);
    }
}
