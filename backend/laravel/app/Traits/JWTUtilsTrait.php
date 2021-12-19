<?php

namespace App\Traits;

use Tymon\JWTAuth\JWTGuard;

trait JWTUtilsTrait
{
  public function guard() : JWTGuard {
    return auth('api');
  }
}
