<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TwoStepCodeResource extends JsonResource
{
  public function __construct(int $code)
  {
    $this->resource = [
      'code' => $code
    ];
  }
}
