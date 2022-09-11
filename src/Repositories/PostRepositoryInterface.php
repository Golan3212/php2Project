<?php

namespace App\Repositories;

use App\User\Entities\Post;

interface PostRepositoryInterface
{
    public function save(Post $post): void;
    public function get(int $id): Post;
}