<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CommentReplyTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_that_post_can_be_created()
    {
        $this->assertDatabaseCount('posts',  2);
    }

    public function test_that_comment_can_be_created()
    {
        $name = $this->faker()->name();
        $comment = $this->faker()->paragraph(3);
        (new Comment())->store(1, $name, $comment);

        $this->assertDatabaseCount('comments',  1);
    }

    /**
     * @dataProvider invalidCommentReplyData
     */

    public function test_comment_reply_creating_data_is_invalid($getData)
    {
        [$invalidData, $testField] = $getData();
        
        $response = $this->post('/api/posts/1/comments/1/replies', $invalidData);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
                ->assertJsonFragment(['success' => false])
                ->assertSeeText($testField, true);
    }

    public function invalidCommentReplyData()
    {
        return [
            'it fails when all fields are null' => [
                fn () => [
                    [], // Fields to be caught as invalid
                    ['name', 'comment', 'required'] // Fields to be caught as invalid
                ]
            ],
            'it fails when all fields are empty' => [
                fn () => [
                    array_map(fn($x) => '', $this->getValidCommentReplyData()), // Fields to be caught as invalid
                    ['name', 'comment', 'required'] // Fields to be caught as invalid
                ]
            ],
            'it fails when name is missing' => [
                fn () => [
                    array_merge($this->getValidCommentReplyData(), ['name' => null]), // Fields to be caught as invalid
                    ['name', 'required'] // Fields to be caught as invalid
                ]
            ],
            'it fails when comment is missing' => [
                fn () => [
                    array_merge($this->getValidCommentReplyData(), ['comment' => null]), // Fields to be caught as invalid
                    ['comment', 'required'] // Fields to be caught as invalid
                ]
            ],
        ];
    }

    private function getValidCommentReplyData()
    {
        $data = [
            'name' => 'Lorem ipsum',
            'comment' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam id mi porttitor, finibus purus eget, rhoncus augue. Fusce feugiat ac quam vitae facilisis.'
        ];

        return $data;
    }

    /**
      * @dataProvider invalidCommentReplyData
     */

    public function test_comment_reply_updating_data_is_invalid($getData)
    {
        [$invalidData, $testField] = $getData();
        
        $response = $this->patch('/api/posts/1/comments/1/replies/1', $invalidData);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
                ->assertJsonFragment(['success' => false])
                ->assertSeeText($testField, true);
    }

    public function test_for_comment_reply_created()
    {
        $name = $this->faker()->name();
        $comment = $this->faker()->paragraph(3);
        $commentId = (new Comment)->store(1, $name, $comment)->commentId;

        $response = $this->post('/api/posts/1/comments/' . $commentId . '/replies', [
            'name' => $name,
            'comment' => $comment
        ]);

        $this->assertDatabaseHas('comments', [
            'commentator' => $name,
            'comment' => $comment,
            'has_parent' => true
        ]);
        $this->assertDatabaseHas('parent_child_comments', [
            'parent_comment_id' => $commentId
        ]);
        
        $response->assertStatus(201)->assertJsonFragment(['success' => true]);
    }

    public function test_for_comment_reply_updated()
    {
        $comment = new Comment();
        // create comment
        $name = $this->faker()->name();
        $message = $this->faker()->paragraph(3);
        $commentId = $comment->store(1, $name, $message)->commentId;

        // create reply
        $replyName = $this->faker()->name();
        $replyComment = $this->faker()->paragraph(3);
        $replyId = $comment->store_reply(1, $commentId, $replyName, $replyComment)->commentId;

        $newName = $this->faker()->name();
        $newComment = $this->faker()->paragraph(2);
        $response = $this->patch('/api/posts/1/comments/' . $commentId . '/replies/' . $replyId, [
            'name' => $newName,
            'comment' => $newComment
        ]);

        $this->assertDatabaseHas('comments', [
            'commentator' => $newName,
            'comment' => $newComment,
            'has_parent' => true
        ]);
        $this->assertDatabaseHas('parent_child_comments', [
            'parent_comment_id' => $commentId,
            'child_comment_id' => $replyId
        ]);
        
        $response->assertStatus(200)->assertJsonFragment(['success' => true]);
    }

    public function test_for_comment_reply_deleted()
    {
        $comment = new Comment();
        // create comment
        $name = $this->faker()->name();
        $message = $this->faker()->paragraph(3);
        $commentId = $comment->store(1, $name, $message)->commentId;

        // create reply
        $replyName = $this->faker()->name();
        $replyComment = $this->faker()->paragraph(3);
        $replyId = $comment->store_reply(1, $commentId, $replyName, $replyComment)->commentId;

        $response = $this->delete('/api/posts/1/comments/' . $commentId . '/replies/' . $replyId);
        
        $response->assertStatus(200)->assertJsonFragment(['success' => true]);
    }
}
