<?php

namespace App\Response;

use JsonSerializable;

abstract class AbstractResponse implements JsonSerializable
{
    abstract protected function payload(): array;

    public function jsonSerialize(): mixed
    {
        return
        [
            'success' => static::SUCCESS,
            ...$this->payload()
        ];
    }
}