<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Factory;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\Http\Parser\Cookies;

class Authenticate extends Middleware
{
    public function __construct(
        Factory $auth,
        private JWT $jwt,
    ) {
        parent::__construct($auth);

        // set the correct name for the session cookie to be read
        foreach ($jwt->parser()->getChain() as &$pcontract) {
            if ($pcontract instanceof Cookies) {
                $pcontract->setKey("session");
            }
        }
    }

    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new AccessDeniedHttpException();
    }
}
