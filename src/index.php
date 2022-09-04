<?php

require_once "User.php";
require_once "Article.php";
require_once "Comment.php";

$user = new User(10, "golan", "golanov");
$article = new Article($user, 'zagolovok', 'text');
$comment = new Comment($user, $article, '4isto comment');

print_r($comment);
