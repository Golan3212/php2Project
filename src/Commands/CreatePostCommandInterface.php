<?php

namespace App\Commands;

use App\Argument\Argument;

interface CreatePostCommandInterface
{
    public function handle(Argument $argument): void;
}