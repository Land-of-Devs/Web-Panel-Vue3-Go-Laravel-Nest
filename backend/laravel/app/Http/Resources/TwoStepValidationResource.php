<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class TwoStepValidationResource extends JsonResource
{
  public function __construct(bool $valid)
  {
    $this->resource = [
      'valid' => $valid
    ];
  }

  /**
   * Customize the response for a request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Illuminate\Http\JsonResponse  $response
   * @return void
   */
  public function withResponse($request, $response)
  {
    if (!$this->resource['valid']) {
      $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
    }
  }
}
