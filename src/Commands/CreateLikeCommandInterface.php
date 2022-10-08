<?php

namespace App\Commands;

use App\Argument\Argument;

interface CreateLikeCommandInterface
{
    public function handle(Argument $argument): void;
}