<?php

namespace App\User\Repositories;

use App\Connection\ConnectorInterface;
use App\Connection\SqLiteConnector;
use App\Date\DateTime;
use App\Exceptions\UserNotFoundException;
use App\User\Entities\User;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    private PDO $connection;

    public function __construct(private ?ConnectorInterface $connector = null)
    {
        $this->connector = $connector ?? new SqLiteConnector();
        $this->connection = $this->connector->getConnection();
    }

    /**
     * @throws UserNotFoundException
     * @throws \Exception
     */
    public function get(int $id): User
    {
        $statement = $this->connection->prepare(
            "select * from user where id = :userId"
        );

        $statement->execute([
            'userId' => $id
        ]);

        $userObj = $statement->fetch(PDO::FETCH_OBJ);

        if(!$userObj)
        {
            throw new UserNotFoundException("User with id : $id not found");
        }

        return $this->mapUser($userObj);
    }

    /**
     * @throws UserNotFoundException
     * @throws \Exception
     */
    public function findUserByEmail(string $email): User
    {
        $statement = $this->connection->prepare(
            "select * from user where email = :email"
        );

        $statement->execute([
            'email' => $email
        ]);

        $userObj = $statement->fetch(PDO::FETCH_OBJ);

        if(!$userObj)
        {
            throw new UserNotFoundException("User with email : $email not found");
        }

        return $this->mapUser($userObj);
    }

    public function mapUser(object $userObj): User
    {
        $user = new User(
            $userObj->email,
            $userObj->first_name,
            $userObj->last_name
        );

        $user
            ->setId($userObj->id)
            ->setActive($userObj->active)
            ->setCreatedAt(new DateTime($userObj->created_at))
            ->setUpdatedAt(($updatedAt = $userObj->updated_at) ? new DateTime($updatedAt) : null)
            ->setDeletedAt(($deletedAt = $userObj->deleted_at) ? new DateTime($deletedAt) : null);

        return $user;
    }
}