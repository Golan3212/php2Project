<?php

use App\Repositories\UserRepository;
use App\Repositories\PostRepository;
use App\Repositories\CommentRepository;
use App\User\Entities\Comment;
use App\User\Entities\Post;
use App\User\Entities\User;

require_once __DIR__ . '/autoload_runtime.php';

$userRepository = new UserRepository();
$postRepository = new PostRepository();
$commentRepository = new CommentRepository();


$user = new User('andrey', 'golanov');
$user = $userRepository->get(32);

$post = new Post('Title example', 'text example', $user->getId());
$postRepository->save($post);
$post = $postRepository->get($user->getId());

$comment = new Comment( 'something text', $post->getId(), $user->getId() );
$commentRepository->save($comment);
$comment = $commentRepository->get($user->getId(), $post->getId() );


// var_dump($comment);
die();