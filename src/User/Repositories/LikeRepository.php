<?php

namespace App\User\Repositories;

use App\Connection\ConnectorInterface;
use App\Connection\SqLiteConnector;
use App\Exceptions\LikeNotFoundException;
use App\User\Entities\Like;
use PDO;

class LikeRepository implements LikeRepositoryInterface
{
    private PDO $connection;

    public function __construct(private ?ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqLiteConnector();
        $this->connection = $this->connector->getConnection();
    }

    /**
     * @throws LikeNotFoundException
     */
    public function getByPostId(int $postId, int $authorId): Like
    {
        $statement = $this->connection->prepare(
            "select * from like where  post_id = :postId and user_id = :authorId"
        );

        $statement->execute([
            'postId' => $postId,
            'authorId' => $authorId
        ]);
        $likeObj = $statement->fetch(PDO::FETCH_OBJ);

        if(!$likeObj)
        {
            throw new LikeNotFoundException("Post with id : $postId  have not likes");
        }
        return $this->mapLike($likeObj);
    }

    public function get( int $authorId, int $postId): Like
    {
        $statement = $this->connection->prepare(
            "select * from like where user_id = :authorId and post_id = :postId"
        );

        $statement->execute([
            'authorId' => $authorId,
            'postId' => $postId
        ]);

        $likeObj = $statement->fetch(PDO::FETCH_OBJ);
       
        $like = new Like ($likeObj->id, $likeObj->post_id, $likeObj->user_id  );

        $like
        ->setId($likeObj->id)
        ->setPostId($likeObj->post_id);
        
              

        return $like;

    }

    public function mapLike(object $likeObj): Like
    {
        $like = new Like(
            $likeObj->id,
            $likeObj->post_id,
            $likeObj->user_id,
        );

        $like
            ->setId($likeObj->id)
            ->setUserId($likeObj->user_id)
            ->setPostId($likeObj->post_id);


        return $like;
    }
}