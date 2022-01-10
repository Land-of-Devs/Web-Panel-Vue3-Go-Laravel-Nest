<?php

namespace App\Http\Middleware;

use App\Domain\Interfaces\Users\UserEntity;
use App\Traits\JWTUtilsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\JWT;

class AdminOnlyGuard
{
  use JWTUtilsTrait;

  public function __construct(
    private JWT $jwt,
  ) {
  }

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
    $adminAccess = $this->jwt->getClaim('AdminAccessToken');
    $now = time();
    
    if ($usr instanceof UserEntity && $usr->getRole() < config('enums.staff_roles.ADMIN')) {
      throw new AccessDeniedHttpException();
    }

    if ($now > $adminAccess) {
      Cookie::queue(Cookie::forget('adminaccess'));
      throw new HttpException(Response::HTTP_UNAUTHORIZED);
    }

    return $next($request);
  }
}
