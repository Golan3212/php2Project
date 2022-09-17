<?php

namespace App\User\Entities;

use App\User\Entities\User;
use App\Traits\Id;
use App\Traits\UserId;


class Post 
{
    use Id;
    use UserId;
   
    
    private User $user;
    
    public function __construct(
        private string $title,
        private string $text,
        private int $authorId
    ) {
        $this->userId = $this->authorId;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(?string $title): string
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthorId(): ?int
    {
        return $this->authorId;
    }


    public function __toString()
    {
        return
            $this->title. '\n '.
            $this->text;
    }
}