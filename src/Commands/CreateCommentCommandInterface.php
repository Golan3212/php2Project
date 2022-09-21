<?php

namespace App\Commands;

use App\Argument\Argument;

interface CreateCommentCommandInterface
{
    public function handle(Argument $argument): void;
}