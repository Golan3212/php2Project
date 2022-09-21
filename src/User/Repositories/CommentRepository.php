<?php

namespace App\User\Repositories;

use App\Connection\ConnectorInterface;
use App\Exceptions\CommentNotFoundException;
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

//    public function save(Comment $comment): void
//    {
//        $statement = $this->connection->prepare(
//            '
//                    insert into comment (post_id, user_id, text)
//                    values (:post_id, :user_id, :text)
//                  '
//        );
//
//        $statement->execute(
//            [
//                ':post_id' => $comment->getPostId(),
//                ':user_id' => $comment->getAuthorId(),
//                ':text' => $comment->getText()
//            ]
//        );
//    }
    public function findCommentByAuthorIdPostId(int $authorId, int $postId, string $text): Comment
    {
        $statement = $this->connection->prepare(
            "select * from comment where user_id = :authorId and post_id = :postId and text = :text"
        );

        $statement->execute([
            'authorId' => $authorId,
            'postId' => $postId,
            'text' => $text
        ]);
        $commentObj = $statement->fetch(PDO::FETCH_OBJ);

        if(!$commentObj)
        {
            throw new CommentNotFoundException("Comment with user_id : $authorId and post_id : $postId not found");
        }
        return $this->mapComment($commentObj);
    }

    public function get( int $authorId, int $postId): Comment
    {
        $statement = $this->connection->prepare(
            "select * from comment where user_id = :userId and post_id = :postId"
        );

        $statement->execute([
            'userId' => $authorId,
            'postId' => $postId
        ]);

        $commentObj = $statement->fetch(PDO::FETCH_OBJ);
       
        $post = new Comment($commentObj->text, $commentObj->post_id, $commentObj->user_id );

        $post
        ->setId($commentObj->id);
        
              

        return $post;

    }

    public function mapComment(object $commentObj): Comment
    {
        $comment = new Comment(
            $commentObj->user_id,
            $commentObj->post_id,
            $commentObj->text
        );

        $comment
            ->setId($commentObj->id)
            ->setUserId($commentObj->user_id)
            ->setPostId($commentObj->post_id);


        return $comment;
    }
}