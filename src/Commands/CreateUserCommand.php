<?php

namespace App\Commands;

use App\Argument\Argument;
use App\Connection\ConnectorInterface;
use App\Connection\SqLiteConnector;
use App\Date\DateTime;
use App\Exceptions\CommandException;
use App\Exceptions\UserNotFoundException;
use App\User\Repositories\UserRepositoryInterface;
use PDO;

class CreateUserCommand implements CreateUserCommandInterface
{
    private PDO $connection;

    public function __construct(
        private UserRepositoryInterface $userRepository,
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
        $email = $argument->get('email');
        $firstName = $argument->get('firstName');
        $lastName = $argument->get('lastName');

        if($this->userExist($email))
        {
            throw new CommandException("User already exist: $email".PHP_EOL);
        }

        $statement = $this->connection->prepare(
            '
                    insert into user (email, first_name, last_name, created_at)
                    values (:email, :first_name, :last_name, :created_at)
                  '
        );

        $statement->execute(
            [
                ':email' => $email,
                ':first_name' => $firstName,
                ':last_name' => $lastName,
                ':created_at' => new DateTime()
            ]
        );
    }

    private function userExist(string $email): bool
    {
        try {
            $this->userRepository->findUserByEmail($email);
        }catch (UserNotFoundException $exception)
        {
            return false;
        }

        return true;
    }
}