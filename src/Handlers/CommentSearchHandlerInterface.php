<?php

namespace App\Handlers;

use App\Request\Request;
use App\Response\AbstractResponse;

interface CommentSearchHandlerInterface
{
    public function handle(Request $request): AbstractResponse;
}