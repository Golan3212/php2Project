<?php

namespace App\Exceptions;

use Exception;

class LikeNotFoundException extends Exception
{
    protected $message = 'Post hav`nt likes';
}