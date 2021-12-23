<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class ErrorResource extends JsonResource
{
    public function __construct(string $msg, private int $code = Response::HTTP_BAD_REQUEST)
    {
        $this->resource = [
            'error' => $msg
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->code);
    }
}
