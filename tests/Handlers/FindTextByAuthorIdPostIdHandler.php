<?php

namespace Test\Handlers;

use App\Exceptions\CommentNotFoundException;
use App\Handlers\CommentSearchHandler;
use App\Handlers\CommentSearchHandlerInterface;
use App\Request\Request;
use App\Response\ErrorResponse;
use App\Response\SuccessResponse;
use App\User\Entities\Comment;
use App\User\Repositories\CommentRepository;
use App\User\Repositories\CommentRepositoryInterface;
use PHPUnit\Framework\TestCase;

class FindTextByAuthorIdPostIdHandler extends TestCase
{
    public function __construct(
//        ?int $authorId = null,
        ?int $postId = null,
        array $data = [],
        $dataName = '',
        private ?CommentRepositoryInterface $commentRepository = null,
        private ?CommentSearchHandlerInterface $commentSearchHandler = null
    )
    {
        $this->commentRepository ??= new CommentRepository();
        $this->commentSearchHandler = $this->postSearchHandler ?? new CommentSearchHandler($this->commentRepository);
        parent::__construct($postId, $data, $dataName);
    }

    public function testItReturnsErrorResponseIfNoPostIdProvided(): void
    {
        $request = new Request([], []);
        $response = $this->commentSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"No such query param in the request: post_id"}'
        );

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsErrorResponseIfCommentNotFound(): void
    {
        $request = new Request(['post_id' => '1', 'text' => 'lorem'], []);
        $response = $this->commentSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"Comment with post_id : 1 and text : lorem not found"}');

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request(['user_id' => '32', 'post_id' => '1', 'text' => 'something text'], []);
        $response = $this->commentSearchHandler->handle($request);

        $this->assertInstanceOf(SuccessResponse::class, $response);
        $this->expectOutputString(
            '{"success":true,"data":{"user_id":32,"post_id":"1","text":"something text"}}');

        echo json_encode($response);
    }

    private function CommentsRepository(array $comments): CommentRepositoryInterface
    {
        return new class($comments) implements CommentRepositoryInterface {
            public function __construct(
                private array $comments
            ) {
            }

            public function save(Comment $comment): void
            {
            }

            public function get(int $authorId, int $postId): Comment
            {
                throw new CommentNotFoundException("Not found");
            }

            public function findCommentByAuthorIdPostId(int $authorId, int $postId, $text): Comment
            {
                foreach ($this->comments as $comment) {
                    if ($comment instanceof Comment && $authorId === $comment->getAuthorId()
                        && $postId === $comment->getPostId()
                        && $text === $comment->getText() )
                    {
                        return $comment;
                    }
                }

                throw new CommentNotFoundException("Not found");
            }
        };
    }
}