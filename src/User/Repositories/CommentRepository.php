<?php

namespace App\User\Repositories;

use App\Connection\ConnectorInterface;
use App\Connection\SqLiteConnector;
use App\User\Entities\Comment;
use PDO;

class CommentRepository implements CommentRepositoryInterface
{
    private PDO $connection;

    public function __construct(private ?ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqLiteConnector();
        $this->connection = $this->connector->getConnection();
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            '
                    insert into comment (post_id, user_id, text)
                    values (:post_id, :user_id, :text)
                  '
        );

        $statement->execute(
            [
                ':post_id' => $comment->getPostId(),
                ':user_id' => $comment->getAuthorId(),
                ':text' => $comment->getText()
            ]
        );
    }


    public function get( int $userId, int $postId): Comment
    {
        $statement = $this->connection->prepare(
            "select * from comment where user_id = :userId and post_id = :postId"
        );

        $statement->execute([
            'userId' => $userId,
            'postId' => $postId
        ]);

        $commentObj = $statement->fetch(PDO::FETCH_OBJ);
       
        $post = new Comment($commentObj->text, $commentObj->post_id, $commentObj->user_id );

        $post
        ->setId($commentObj->id);
        
              

        return $post;

    }
}