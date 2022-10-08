<?php

namespace App\Handlers;

use App\Date\DateTime;
use App\Exceptions\UserNotFoundException;
use App\Request\Request;
use App\Response\AbstractResponse;
use App\Response\ErrorResponse;
use App\Response\SuccessResponse;
use App\User\Repositories\UserRepositoryInterface;
use Exception;
use Psr\Log\LoggerInterface;

class UserSearchHandler implements UserSearchHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private LoggerInterface $logger
    ) {
    }

    public function handle(Request $request): AbstractResponse
    {
        $this->logger->debug('Start user search '.(new DateTime())->format('d.m.Y H:i:s'));

        try {
            $email = $request->query('email');
        }catch (Exception $exception)
        {
            $this->logger->error($exception->getMessage());
            return  new ErrorResponse($exception->getMessage());
        }

        try {
            $user = $this->userRepository->findUserByEmail($email);
        }catch (UserNotFoundException $exception)
        {
            return new ErrorResponse($exception->getMessage());
        }

        $this->logger->info('User found : '. $user->getId());
        $this->logger->debug('Finish user search '.(new DateTime())->format('d.m.Y H:i:s'));

        return new SuccessResponse(
            [
                'email' => $user->getEmail(),
                'name' => $user->getFirstName() . ' ' . $user->getLastName()
            ]
        );
    }
}