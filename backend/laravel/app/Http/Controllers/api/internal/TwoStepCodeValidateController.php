<?php

namespace App\Http\Controllers\api\internal;

use App\Http\Requests\TwoStepCodeValidateRequest;
use RobThree\Auth\TwoFactorAuth;

use Illuminate\Http\Request;

class TwoStepCodeValidateController extends Controller
{
    use ApiResponseTrait;

    private $tfa;

    public function __construct() {
        $this->tfa = new TwoFactorAuth();
    }

    /**
     * Verify the 2fa code for the given user.
     *
     * @param  TwoStepCodeValidateRequest  $req
     * @return \Illuminate\Http\Response
     */
    public function verify(TwoStepCodeValidateRequest $data)
    {
        try {
            $data = $this->productRepository->find($id);
            if(is_null($data)){
                $msg = 'Product Not Found';
                return self::apiResponseError(null, $msg , $this->not_found);
            }
            return self::apiResponseSuccess($data, 'See product details!');
        } catch (\Exception $e) {
            return self::apiServerError($e->getMessage());
        }
    }
}
TwoStepCodeValidateRequest
