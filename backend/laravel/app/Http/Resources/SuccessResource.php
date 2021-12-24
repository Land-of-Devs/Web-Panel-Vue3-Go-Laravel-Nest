<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class SuccessResource extends JsonResource
{
    public function __construct(string $msg, private int $code = Response::HTTP_OK, object $result)
    {
        $this->resource = [
            'success' => $msg,
            'count' => $result->count,
            'efected' => $result->keys
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->code);
    }
}
