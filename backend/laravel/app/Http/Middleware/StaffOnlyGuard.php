<?php

namespace App\Http\Middleware;

use App\Domain\Interfaces\Users\UserEntity;
use App\Traits\JWTUtilsTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class StaffOnlyGuard
{
  use JWTUtilsTrait;

  /**
   * Handle the incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  callable  $next
   * @return \Illuminate\Http\Response
   */
  public function handle(Request $request, $next)
  {
    $usr = $this->guard()->user();

    if ($usr instanceof UserEntity && $usr->getRole() < config('enums.staff_roles.EMPLOYEE')) { /* TODO: proper enum for roles */
      throw new AccessDeniedHttpException();
    }

    return $next($request);
  }
}
