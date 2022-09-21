<?php

namespace App\User\Repositories;

use App\User\Entities\User;

interface UserRepositoryInterface
{
    public function get(int $id): User;
    public function findUserByEmail(string $email): User;
}