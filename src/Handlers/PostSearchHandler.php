<?php

namespace App\Handlers;

use App\Date\DateTime;
use App\Exceptions\PostNotFoundException;
use App\Request\Request;
use App\Response\AbstractResponse;
use App\Response\ErrorResponse;
use App\Response\SuccessResponse;
use App\User\Repositories\PostRepositoryInterface;
use Exception;
use Psr\Log\LoggerInterface;

class PostSearchHandler implements PostSearchHandlerInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
        private LoggerInterface $logger
    )
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        $this->logger->debug('Start post search '.(new DateTime())->format('d.m.Y H:i:s'));

        try {
            $authorId = $request->query('user_id');
            $title = $request->query('title');
        }catch (Exception $exception)
        {
            $this->logger->error($exception->getMessage());
            return  new ErrorResponse($exception->getMessage());
        }

        try {
            $post = $this->postRepository->findPostByAuthorId($authorId, $title);
        }catch (PostNotFoundException $exception)
        {
            $this->logger->error($exception->getMessage());
            return new ErrorResponse($exception->getMessage());
        }

        $this->logger->info('Post found : user_id '. $post->getUserId() . ', title ' . $post->getTitle() . ', text ' . $post->getText());
        $this->logger->debug('Finish post search '.(new DateTime())->format('d.m.Y H:i:s'));

        return new SuccessResponse(
            [
                'user_id' => $post->getAuthorId(),
                'title' => $post->getTitle(),
                'text' => $post->getText()
            ]
        );
    }
}