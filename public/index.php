<?php

use App\Commands\CreatePostCommand;
use App\User\Repositories\PostRepository;
//use App\User\Repositories\PostRepository;
//use App\User\Repositories\CommentRepository;
//use App\User\Entities\Comment;
//use App\User\Entities\Post;
//use App\User\Entities\User;
use App\Exceptions\CommandException;

require_once __DIR__ . '/autoload_runtime.php';

$postRepository = new PostRepository();
//$postRepository = new PostRepository();
//$commentRepository = new CommentRepository();
//
//
//$user = new User('andrey', 'golanov');
//$user = $userRepository->get(32);
//
//$post = new Post('Title example', 'text example', $user->getId());
//$postRepository->save($post);
//$post = $postRepository->get($user->getId());
//
//$comment = new Comment( 'something text', $post->getId(), $user->getId() );
//$commentRepository->save($comment);
//$comment = $commentRepository->get($user->getId(), $post->getId() );
//

// var_dump($comment);
$command = new CreatePostCommand($postRepository);

try {
    $command->handle(\App\Argument\Argument::fromArgv($argv));
    echo "atata";
}catch (CommandException $commandException)
{
    echo $commandException->getMessage();
}
die();