<?php

namespace App\Commands;

use App\Argument\Argument;
use App\Connection\ConnectorInterface;
use App\Connection\SqLiteConnector;
use App\Exceptions\CommandException;
use App\Exceptions\PostNotFoundException;
use App\User\Repositories\PostRepositoryInterface;
use PDO;

class CreatePostCommand implements CreatePostCommandInterface
{
    private PDO $connection;

    public function __construct(
        private PostRepositoryInterface $postRepository,
        private ?ConnectorInterface $connector = null
    )
    {
        $this->connector = $connector ?? new SqLiteConnector(databaseConfig()['sqlite']['DATABASE_URL']);
        $this->connection = $this->connector->getConnection();
    }

    /**
     * @throws CommandException
     */
        public function handle(Argument $argument): void
    {
        $authorId = $argument->get('authorId');
        $title = $argument->get('title');
        $text = $argument->get('text');


        if($this->postExist($authorId, $title))
        {
            throw new CommandException("User already publicated: $title".PHP_EOL);

        }

        $statement = $this->connection->prepare(
            '
                    insert into post (user_id, title, text)
                    values (:authorId, :title, :text)
                  '
        );

        $statement->execute(
            [
                ':authorId' => $authorId,
                ':title' => $title,
                ':text' => $text
            ]
        );
    }

    private function postExist(int $authorId, string $title): bool
    {
        try {
            $this->postRepository->findPostByAuthorId($authorId, $title);
        }catch (PostNotFoundException $exception)
        {
            return false;
        }

        return true;
    }
}