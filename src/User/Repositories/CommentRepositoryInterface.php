<?php

namespace App\User\Repositories;

use App\User\Entities\Comment;

interface CommentRepositoryInterface
{
    public function get(int $authorId, int $postId): Comment;
    public function findCommentByAuthorIdPostId(int $authorId, int $postId, string $text): Comment;
}