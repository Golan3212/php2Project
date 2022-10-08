<?php

namespace App\Commands;

use App\Argument\Argument;
use App\Connection\ConnectorInterface;
use App\Connection\SqLiteConnector;
use App\Exceptions\CommandException;
use App\Exceptions\LikeNotFoundException;
use App\User\Repositories\LikeRepositoryInterface;
use PDO;

class CreateLikeCommand implements CreateLikeCommandInterface
{
    private PDO $connection;

    public function __construct(
        private LikeRepositoryInterface $likeRepository,
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



        if($this->likeExist($authorId, $postId))
        {
            throw new CommandException("User already liked this post".PHP_EOL);

        }

        $statement = $this->connection->prepare(
            '
                    insert into like (user_id, post_id)
                    values (:authorId, :postId)
                  '
        );

        $statement->execute(
            [
                ':authorId' => $authorId,
                ':postId' => $postId
            ]
        );
    }

    private function likeExist(int $postId, int $authorId): bool
    {
        try {
            $this->likeRepository->getByPostId($postId, $authorId);
        }catch (LikeNotFoundException $exception)
        {
            return false;
        }

        return true;
    }
}