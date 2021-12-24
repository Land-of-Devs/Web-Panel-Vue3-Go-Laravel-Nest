<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    public function __construct(object $list)
    {
        $this->resource = [
            'list' => $list
        ];
    }
}
