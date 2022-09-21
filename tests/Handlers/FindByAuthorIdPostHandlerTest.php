<?php

namespace Test\Handlers;

use App\Exceptions\PostNotFoundException;
use App\Handlers\PostSearchHandler;
use App\Handlers\PostSearchHandlerInterface;
use App\Request\Request;
use App\Response\ErrorResponse;
use App\Response\SuccessResponse;
use App\User\Entities\Post;
use App\User\Repositories\PostRepository;
use App\User\Repositories\PostRepositoryInterface;
use PHPUnit\Framework\TestCase;

class FindByAuthorIdPostHandlerTest extends TestCase
{
    public function __construct(
        ?int $authorId = null,
        array $data = [],
        $dataName = '',
        private ?PostRepositoryInterface $postRepository = null,
        private ?PostSearchHandlerInterface $postSearchHandler = null
    )
    {
        $this->postRepository ??= new PostRepository();
        $this->postSearchHandler = $this->postSearchHandler ?? new PostSearchHandler($this->postRepository);
        parent::__construct($authorId, $data, $dataName);
    }

    public function testItReturnsErrorResponseIfNoPostProvided(): void
    {
        $request = new Request([], []);
        $response = $this->postSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"No such query param in the request: user_id"}'
        );

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsErrorResponseIfPostNotFound(): void
    {
        $request = new Request(['user_id' => '35', 'title' => 'Title1'], []);
        $response = $this->postSearchHandler->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString(
            '{"success":false,"reason":"Post with user_id : 35 and title : Title1 not found"}');

        echo json_encode($response);
    }

    /**
     * @throws \JsonException
     */
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request(['user_id' => '32', 'title' => 'Title'], []);
        $response = $this->postSearchHandler->handle($request);

        $this->assertInstanceOf(SuccessResponse::class, $response);
        $this->expectOutputString(
            '{"success":true,"data":{"user_id":32,"title":"Title","text":"text"}}');

        echo json_encode($response);
    }

    private function postsRepository(array $posts): postRepositoryInterface
    {
        return new class($posts) implements PostRepositoryInterface {
            public function __construct(
                private array $posts
            ) {
            }

            public function save(Post $post): void
            {
            }

            public function get(int $authorId): Post
            {
                throw new PostNotFoundException("Not found");
            }

            public function findPostByAuthorId(int $authorId, string $title): Post
            {
                foreach ($this->posts as $post) {
                    if ($post instanceof Post && $authorId === $post->getAuthorId() && $title === $post->getTitle()) {
                        return $post;
                    }
                }

                throw new PostNotFoundException("Not found");
            }
        };
    }
}