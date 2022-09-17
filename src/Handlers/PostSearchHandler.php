<?php

namespace App\Handlers;

use App\Exceptions\PostNotFoundException;
use App\Request\Request;
use App\Response\AbstractResponse;
use App\Response\ErrorResponse;
use App\Response\SuccessResponse;
use App\User\Repositories\PostRepositoryInterface;
use Exception;

class PostSearchHandler implements PostSearchHandlerInterface
{
    public function __construct(private PostRepositoryInterface $postRepository)
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try {
            $authorId = $request->query('user_id');
            $title = $request->query('title');
        }catch (Exception $exception)
        {
            return  new ErrorResponse($exception->getMessage());
        }

        try {
            $post = $this->postRepository->findPostByAuthorId($authorId, $title);
        }catch (PostNotFoundException $exception)
        {
            return new ErrorResponse($exception->getMessage());
        }

        return new SuccessResponse(
            [
                'user_id' => $post->getAuthorId(),
                'title' => $post->getTitle(),
                'text' => $post->getText()
            ]
        );
    }
}