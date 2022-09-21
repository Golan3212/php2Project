<?php

namespace App\Commands;

use App\Argument\Argument;

interface CreateUserCommandInterface
{
    public function handle(Argument $argument): void;
}