<?php

namespace App\User\Entities;

use App\User\Entities\User;
use App\Traits\Id;
use App\User\Entities\Post;

class Comment
{
    use Id;
   


    public function __construct(
       
        private string $text,
        private ?int $postId,
        private ?int $authorId
        
    ) {
       
    }

    public function getPostId(): ?int
    {
        return $this->postId;
    }

    public function getAuthorId(): ?int
    {
        return $this->authorId;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    

    public function __toString()
    {
        return
            $this->postId . '\n '.
            $this->text . '\n ' .
            $this->authorId . '\n ';
    }
}