<?php

//use App\Authentification\AuthentificationInterface;
//use App\Authentification\JsonBodyAuthentification;
//use App\Authentification\PasswordAuthentification;
//use App\Authentification\TokenAuthentification;
//use App\Commands\CreateAuthTokenCommand;
//use App\Commands\CreateAuthTokenCommandInterface;
use App\Commands\CreateUserCommand;
use App\Commands\CreateUserCommandInterface;
use App\Connection\ConnectorInterface;
use App\Connection\SqLiteConnector;
use App\Container\DiContainer;
//use App\Handlers\LoginHandler;
//use App\Handlers\LoginHandlerInterface;
//use App\Handlers\UserCreateHandler;
//use App\Handlers\UserCreateHandlerInterface;
use App\Handlers\UserSearchHandler;
use App\Handlers\UserSearchHandlerInterface;
use App\Handlers\PostSearchHandler;
use App\Handlers\PostSearchHandlerInterface;
//use App\Repositories\AuthTokenRepository;
//use App\Repositories\AuthTokenRepositoryInterface;
use App\Request\Request;
use App\User\Repositories\CommentRepository;
use App\User\Repositories\CommentRepositoryInterface;
use App\User\Repositories\PostRepository;
use App\User\Repositories\PostRepositoryInterface;
use App\User\Repositories\UserRepository;
use App\User\Repositories\UserRepositoryInterface;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../database/config/config.php';

Dotenv::createImmutable(__DIR__.'/../')->safeLoad();

$request = new Request($_GET, $_POST, $_SERVER, $_COOKIE);

$container = new DiContainer();

$container->bind(PDO::class, new PDO(databaseConfig()['sqlite']['DATABASE_URL']));
$container->bind(
    SqLiteConnector::class, new SqLiteConnector(databaseConfig()['sqlite']['DATABASE_URL']));

$logger = new Logger('php2_logger');

$isNeedLogToFile = $_SERVER['LOG_TO_FILE'] === 'true';
$isNeedLogToConsole = $_SERVER['LOG_TO_CONSOLE'] === 'true';

if($isNeedLogToFile)
{
    $logger->pushHandler(new StreamHandler(
        __DIR__ . '/../var/log/php2.log',
        Level::Info
    ))
        ->pushHandler(new StreamHandler(
            __DIR__ . '/../var/log/php2.debug.log',
            Level::Debug
        ))
        ->pushHandler(new StreamHandler(
            __DIR__ . '/../var/log/php2.error.log',
            Level::Error
        ));

}


if($isNeedLogToConsole)
{
    $logger->pushHandler(new StreamHandler("php://stdout"));
}

$container->bind(LoggerInterface::class, $logger);
$container->bind(ConnectorInterface::class, SqLiteConnector::class);
$container->bind(UserRepositoryInterface::class, UserRepository::class);
$container->bind(UserSearchHandlerInterface::class, UserSearchHandler::class);
$container->bind(PostSearchHandlerInterface::class, PostSearchHandler::class);
$container->bind(PostRepositoryInterface::class, PostRepository::class);
$container->bind(CommentRepositoryInterface::class, CommentRepository::class);

//$container->bind(UserCreateHandlerInterface::class, UserCreateHandler::class);
//$container->bind(CreateUserCommandInterface::class, CreateUserCommand::class);
//$container->bind(AuthentificationInterface::class, TokenAuthentification::class);
//$container->bind(CreateAuthTokenCommandInterface::class, CreateAuthTokenCommand::class);
//$container->bind(LoginHandlerInterface::class, LoginHandler::class);
//$container->bind(AuthTokenRepositoryInterface::class, AuthTokenRepository::class);

return $container;