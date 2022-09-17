<?php

namespace App\User\Repositories;

use App\User\Entities\Post;

interface PostRepositoryInterface
{

    public function get(int $id): Post;
    public function findPostByAuthorId(int $authorId, string $title): Post;

}