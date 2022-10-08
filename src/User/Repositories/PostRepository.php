<?php

namespace App\User\Repositories;

use App\Connection\ConnectorInterface;
use App\Connection\SqLiteConnector;
use App\Exceptions\PostNotFoundException;
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


    /**
     * @throws PostNotFoundException
     * @throws \Exception
     */

    public function get( int $authorId): Post
    {
        $statement = $this->connection->prepare(
            "select * from post where user_id = :authorId"
        );

        $statement->execute([
            'authorId' => $authorId
        ]);

        $postObj = $statement->fetch(PDO::FETCH_OBJ);

        $post = new Post($postObj->title, $postObj->text, $postObj->user_id );

        $post
        ->setId($postObj->id);
       
        return $this->mapPost($postObj);

    }

    public function mapPost(object $postObj): Post
    {
        $post = new Post(
            $postObj->title,
            $postObj->text,
            $postObj->user_id
        );

        $post
            ->setId($postObj->id)
            ->setUserId($postObj->user_id);


        return $post;
    }

    /**
     * @throws PostNotFoundException
     * @throws \Exception
     */

    public function findPostByAuthorId(int $authorId, string $title): Post
    {
        $statement = $this->connection->prepare(
            "select * from post where user_id = :authorId and title = :title"
        );
//        var_dump($authorId);
//        die();
        $statement->execute([
            'authorId' => $authorId,
            'title' => $title
        ]);

        $postObj = $statement->fetch(PDO::FETCH_OBJ);

        if(!$postObj)
        {
            throw new PostNotFoundException("Post with user_id : $authorId and title : $title not found");
        }

        return $this->mapPost($postObj);
    }
}