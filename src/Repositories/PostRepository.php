<?php

namespace App\Repositories;

use App\Connection\ConnectorInterface;
use App\Connection\SqLiteConnector;
use App\User\Entities\Post;
use PDO;

class PostRepository implements PostRepositoryInterface
{
    private PDO $connection;

    public function __construct(private ?ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqLiteConnector();
        $this->connection = $this->connector->getConnection();
    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            '
                    insert into post (user_id, title, text)
                    values (:authorId, :title, :text)
                  '
        );

        $statement->execute(
            [
                ':authorId' => $post->getUserId(),
                ':title' => $post->getTitle(),
                ':text' => $post->getText()
            ]
        );
    }


    public function get( int $id): Post
    {
        $statement = $this->connection->prepare(
            "select * from post where user_id = :authorId"
        );

        $statement->execute([
            'authorId' => $id
        ]);

        $postObj = $statement->fetch(PDO::FETCH_OBJ);
        // var_dump($postObj->id);
        // die;
        $post = new Post($postObj->title, $postObj->text, $postObj->user_id );

        $post
        ->setId($postObj->id);
       
        return $post;

    }
}