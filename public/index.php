<?php

/** @var ContainerInterface $container */
/** @var Request $request */

//use App\Handlers\LoginHandlerInterface;
//use App\Handlers\UserCreateHandlerInterface;
use App\Handlers\UserSearchHandlerInterface;
use App\Handlers\PostSearchHandlerInterface;
use App\Request\Request;
use App\User\Repositories\UserRepositoryInterface;
use App\User\Repositories\PostRepositoryInterface;
use Psr\Container\ContainerInterface;

$container = require_once __DIR__ . '/autoload_runtime.php';

//$handler = $container->get(LoginHandlerInterface::class);
//
//$handler->handle($request);

$userRepository = $container->get(UserRepositoryInterface::class);
$postRepository = $container->get(PostRepositoryInterface::class);




///** @var UserSearchHandlerInterface $handler */
//$handler = $container->get(UserSearchHandlerInterface::class);
//$handler->handle($request);

/** @var PostSearchHandlerInterface $handler */
$handler = $container->get(PostSearchHandlerInterface::class);
$handler->handle($request);