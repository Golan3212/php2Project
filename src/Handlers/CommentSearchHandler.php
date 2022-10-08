<?php

namespace App\Handlers;

use App\Date\DateTime;
use App\Exceptions\CommentNotFoundException;
use App\Request\Request;
use App\Response\AbstractResponse;
use App\Response\ErrorResponse;
use App\Response\SuccessResponse;
use App\User\Repositories\CommentRepositoryInterface;
use Exception;
use Psr\Log\LoggerInterface;

class CommentSearchHandler implements CommentSearchHandlerInterface
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository,
        private LoggerInterface $logger)
    {
    }

    public function handle(Request $request): AbstractResponse
    {

        $this->logger->debug('Start user search '.(new DateTime())->format('d.m.Y H:i:s'));

        try {
            $authorId = $request->query('user_id');
            $postId = $request->query('post_id');
            $text = $request->query('text');
        }catch (Exception $exception)
        {
            $this->logger->error($exception->getMessage());
            return  new ErrorResponse($exception->getMessage());
        }

        try {
            $comment = $this->commentRepository->findCommentByAuthorIdPostId($authorId, $postId, $text);
        }catch (CommentNotFoundException $exception)
        {
            return new ErrorResponse($exception->getMessage());
        }

        $this->logger->info('User found : '. $comment->getId());
        $this->logger->debug('Finish user search '.(new DateTime())->format('d.m.Y H:i:s'));

        return new SuccessResponse(
            [
                'user_id' => $comment->getAuthorId(),
                'post_id' => $comment->getPostId(),
                'text' => $comment->getText()
            ]
        );
    }
}