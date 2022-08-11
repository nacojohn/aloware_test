<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_that_post_can_be_created()
    {
        $this->assertDatabaseCount('posts',  2);
    }

    /**
     * @dataProvider invalidCommentData
     */

    public function test_comment_creating_data_is_invalid($getData)
    {
        [$invalidData, $testField] = $getData();
        
        $response = $this->post('/api/posts/1/comments', $invalidData);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
                ->assertJsonFragment(['success' => false])
                ->assertSeeText($testField, true);
    }

    public function invalidCommentData()
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
                    array_map(fn($x) => '', $this->getValidCommentData()), // Fields to be caught as invalid
                    ['name', 'comment', 'required'] // Fields to be caught as invalid
                ]
            ],
            'it fails when name is missing' => [
                fn () => [
                    array_merge($this->getValidCommentData(), ['name' => null]), // Fields to be caught as invalid
                    ['name', 'required'] // Fields to be caught as invalid
                ]
            ],
            'it fails when comment is missing' => [
                fn () => [
                    array_merge($this->getValidCommentData(), ['comment' => null]), // Fields to be caught as invalid
                    ['comment', 'required'] // Fields to be caught as invalid
                ]
            ],
        ];
    }

    private function getValidCommentData()
    {
        $data = [
            'name' => 'Lorem ipsum',
            'comment' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam id mi porttitor, finibus purus eget, rhoncus augue. Integer pulvinar sit amet leo nec viverra. Quisque pretium enim et lectus commodo, ut commodo purus tempus. Morbi ac hendrerit sapien. Duis sed massa lorem. Fusce feugiat ac quam vitae facilisis.'
        ];

        return $data;
    }

    /**
     * @dataProvider invalidCommentData
     */

    public function test_comment_updating_data_is_invalid($getData)
    {
        [$invalidData, $testField] = $getData();
        
        $response = $this->patch('/api/posts/1/comments/1', $invalidData);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
                ->assertJsonFragment(['success' => false])
                ->assertSeeText($testField, true);
    }

    public function test_for_comment_created()
    {
        $name = $this->faker()->name();
        $comment = $this->faker()->paragraph(3);

        $response = $this->post('/api/posts/1/comments', [
            'name' => $name,
            'comment' => $comment
        ]);

        $this->assertDatabaseHas('comments', [
            'commentator' => $name,
            'comment' => $comment,
            'has_parent' => false
        ]);
        
        $response->assertStatus(201)->assertJsonFragment(['success' => true]);
    }

    public function test_for_comment_updated()
    {
        $name = $this->faker()->name();
        $comment = $this->faker()->paragraph(3);

        $commentId = (new Comment)->store(1, $name, $comment)->commentId;

        $newName = $this->faker()->name();
        $newComment = $this->faker()->paragraph(2);
        $response = $this->patch('/api/posts/1/comments/' . $commentId, [
            'name' => $newName,
            'comment' => $newComment
        ]);

        $this->assertDatabaseHas('comments', [
            'commentator' => $newName,
            'comment' => $newComment,
            'has_parent' => false
        ]);
        
        $response->assertStatus(200)->assertJsonFragment(['success' => true]);
    }

    public function test_for_comment_deleted()
    {
        $name = $this->faker()->name();
        $comment = $this->faker()->paragraph(3);
        $commentId = (new Comment)->store(1, $name, $comment)->commentId;

        $response = $this->delete('/api/posts/1/comments/' . $commentId);
        
        $response->assertStatus(200)->assertJsonFragment(['success' => true]);
    }

    public function test_for_comments_are_retrieved()
    {
        $response = $this->get('/api/posts/1/comments');
        
        $response->assertStatus(200)->assertJsonFragment(['success' => true]);
    }
}
