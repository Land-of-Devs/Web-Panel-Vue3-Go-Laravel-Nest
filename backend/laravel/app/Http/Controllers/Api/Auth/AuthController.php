<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\AuthRepository;
use App\Traits\ApiResponseTrait;
use App\Traits\JWTUtilsTrait;
use Tymon\JWTAuth\JWTGuard;

class AuthController extends Controller
{
    use ApiResponseTrait, JWTUtilsTrait;

    /* Info de la sesion actual */
    public function info() {
        $usr = $this->guard()->user();
        //var_dump($usr);
        $g = $this->guard();
        // genera un nuevo token (pero no lo pone en las cookies)
        //echo $g->refresh();

        return self::apiResponseSuccess($usr);
    }
}
