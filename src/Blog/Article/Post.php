<?php

namespace App\Blog\Article\Article;

use App\User\Entities\User;

class Post
{
    public function __construct(
        private User $author,
        private string $text
    ) {
    }

    public function __toString()
    {
        return $this->author . ' пишет: ' . $this->text;
    }
}