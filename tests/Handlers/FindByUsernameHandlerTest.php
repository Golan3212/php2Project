<?php

namespace Test\Handlers;

use App\Exceptions\UserNotFoundException;
use App\Handlers\UserSearchHandler;
use App\Handlers\UserSearchHandlerInterface;
use App\Request\Request;
use App\Response\ErrorResponse;
use App\Response\SuccessResponse;
use App\User\Entities\User;
use App\User\Repositories\UserRepository;
use App\User\Repositories\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class FindByUsernameHandlerTest extends TestCase
{
    public function __construct(
        ?string $name = null,
        array $data = [],
        $dataName = '',
        private ?UserRepositoryInterface $userRepository = null,
        private ?UserSearchHandlerInterface $userSearchHandler = null
    )
    {
        $this->userRepository ??= new UserRepository();
        $this->userSearchHandler = $this->userSearchHandler ?? new UserSearchHandler($this->userRepository);
        parent::__construct($name, $data, $dataName);
    }

    public function testItReturnsErrorResponseIfNoUsernameProvided(): void
    {
        $request = new Request([], []);
        $response = $this->userSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"No such query param in the request: email"}'
        );

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsErrorResponseIfUserNotFound(): void
    {
        $request = new Request(['email' => 'fadeev123123@example.ru'], []);
        $response = $this->userSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"User with email : fadeev123123@example.ru not found"}');

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request(['email' => 'atata@.com'], []);
        $response = $this->userSearchHandler->handle($request);

        $this->assertInstanceOf(SuccessResponse::class, $response);
        $this->expectOutputString(
            '{"success":true,"data":{"email":"atata@.com","name":"Andrey Golanov"}}');

        echo json_encode($response);
    }

    private function usersRepository(array $users): UserRepositoryInterface
    {
        return new class($users) implements UserRepositoryInterface {
            public function __construct(
                private array $users
            ) {
            }

            public function save(User $user): void
            {
            }

            public function get(int $id): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function findUserByEmail(string $email): User
            {
                foreach ($this->users as $user) {
                    if ($user instanceof User && $email === $user->getEmail()) {
                        return $user;
                    }
                }

                throw new UserNotFoundException("Not found");
            }
        };
    }
}