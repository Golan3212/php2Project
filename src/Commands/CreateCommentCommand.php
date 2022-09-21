<?php

namespace App\Commands;

use App\Argument\Argument;
use App\Connection\ConnectorInterface;
use App\Connection\SqLiteConnector;
use App\Exceptions\CommandException;
use App\Exceptions\CommentNotFoundException;
use App\User\Repositories\CommentRepositoryInterface;
use PDO;

class CreateCommentCommand implements CreateCommentCommandInterface
{
    private PDO $connection;

    public function __construct(
        private CommentRepositoryInterface $commentRepository,
        private ?ConnectorInterface $connector = null
    )
    {
        $this->connector = $connector ?? new SqLiteConnector();
        $this->connection = $this->connector->getConnection();
    }

    /**
     * @throws CommandException
     */
        public function handle(Argument $argument): void
    {
        $authorId = $argument->get('authorId');
        $postId = $argument->get('postId');
        $text = $argument->get('text');


        if($this->commentExist($authorId, $postId, $text))
        {
            throw new CommandException("User already commented this post".PHP_EOL);

        }

        $statement = $this->connection->prepare(
            '
                    insert into comment (user_id, post_id, text)
                    values (:authorId, :postId, :text)
                  '
        );

        $statement->execute(
            [
                ':authorId' => $authorId,
                ':postId' => $postId,
                ':text' => $text
            ]
        );
    }

    private function commentExist(int $authorId, int $postId, string $text): bool
    {
        try {
            $this->commentRepository->findCommentByAuthorIdPostId($authorId, $postId, $text);
        }catch (CommentNotFoundException $exception)
        {
            return false;
        }

        return true;
    }
}