<?php

namespace App\Repositories;

use App\User\Entities\Comment;

interface CommentRepositoryInterface
{
    public function save(Comment $text): void;
    public function get(int $userId, int $postId): Comment;
}