<?php

namespace App\Handlers;

use App\Exceptions\CommentNotFoundException;
use App\Request\Request;
use App\Response\AbstractResponse;
use App\Response\ErrorResponse;
use App\Response\SuccessResponse;
use App\User\Repositories\CommentRepositoryInterface;
use Exception;

class CommentSearchHandler implements CommentSearchHandlerInterface
{
    public function __construct(private CommentRepositoryInterface $commentRepository)
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try {
            $authorId = $request->query('user_id');
            $postId = $request->query('post_id');
            $text = $request->query('text');
        }catch (Exception $exception)
        {
            return  new ErrorResponse($exception->getMessage());
        }

        try {
            $comment = $this->commentRepository->findCommentByAuthorIdPostId($authorId, $postId, $text);
        }catch (CommentNotFoundException $exception)
        {
            return new ErrorResponse($exception->getMessage());
        }

        return new SuccessResponse(
            [
                'user_id' => $comment->getAuthorId(),
                'post_id' => $comment->getPostId(),
                'text' => $comment->getText()
            ]
        );
    }
}