<?php

namespace App\Response;
use App\Response\AbstractResponse;

class SuccessResponse extends AbstractResponse
{
    protected const SUCCESS = true;

    public function __construct(private readonly array $data)
    {
    }

    protected function payload(): array
    {
        return ['data' => $this->data];
    }
}