<?php

namespace App\Response;

class ErrorResponse extends AbstractResponse
{
    protected const SUCCESS = false;

    public function __construct(
        private string $reason = 'Something goes wrong'
    ) {
    }

    protected function payload(): array
    {
        return ['reason' => $this->reason];
    }
}