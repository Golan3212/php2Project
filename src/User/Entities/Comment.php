<?php

namespace App\User\Entities;


use App\Traits\Id;
use App\Traits\UserId;


class Comment
{
    use Id;
    use UserId;
   


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

    public function setPostId(?int $postId): ?int
    {
        $this->postId = $postId;

        return $this;
    }

    

    public function __toString()
    {
        return
            $this->postId . '\n '.
            $this->text . '\n ' .
            $this->authorId . '\n ';
    }
}