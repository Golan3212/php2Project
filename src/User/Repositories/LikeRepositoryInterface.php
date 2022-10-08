<?php

namespace App\User\Repositories;

use App\User\Entities\Like;

interface LikeRepositoryInterface
{
    public function get(int $postId, int $authorId ): Like;

}