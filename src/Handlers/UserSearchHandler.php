<?php

namespace App\Handlers;

use App\Exceptions\UserNotFoundException;
use App\Request\Request;
use App\Response\AbstractResponse;
use App\Response\ErrorResponse;
use App\Response\SuccessResponse;
use App\User\Repositories\UserRepositoryInterface;
use Exception;

class UserSearchHandler implements UserSearchHandlerInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function handle(Request $request): AbstractResponse
    {
        try {
            $email = $request->query('email');
        }catch (Exception $exception)
        {
            return  new ErrorResponse($exception->getMessage());
        }

        try {
            $user = $this->userRepository->findUserByEmail($email);
        }catch (UserNotFoundException $exception)
        {
            return new ErrorResponse($exception->getMessage());
        }

        return new SuccessResponse(
            [
                'email' => $user->getEmail(),
                'name' => $user->getFirstName() . ' ' . $user->getLastName()
            ]
        );
    }
}