<?php

namespace App\User\Entities;


use App\Traits\Id;
use App\Traits\UserId;


class Like
{
    use Id;
    use UserId;
   


    public function __construct(
       
        private ?int $id,
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setPostId($postId): ?int
    {
        $this->$postId = $postId;

        return $this->$postId;
    }

    public function __toString()
    {
        return
            $this->id . '\n '.
            $this->postId. '\n ' .
            $this->authorId . '\n ';
    }
}