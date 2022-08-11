<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    use HasFactory;
    public $postId;
    public $commentId;

    public function store($post_id, $commentator, $comment)
    {
        $this->postId = $post_id;
        $this->commentId = $this->save_record($post_id, $commentator, $comment);

        return $this;
    }

    public function storeGetId($post_id, $commentator, $comment)
    {
        return $this->save_record($post_id, $commentator, $comment);
    }

    public function update_record($comment_id, $post_id, $commentator, $comment)
    {
        $numberOfRows = DB::table('comments')
                        ->where('id', $comment_id)
                        ->update([
                            'commentator' => ucfirst($commentator),
                            'comment' => ucfirst($comment),
                            'updated_at' => Carbon::now()
                        ]);

        if ($numberOfRows > 0) {
            $this->postId = $post_id;
            $this->commentId = $comment_id;

            return $this;
        }

        return false;
    }

    public function delete_record($comment_id, $post_id)
    {
        $numberOfRows = DB::table('comments')
                        ->where('post_id', $post_id)
                        ->where('id', $comment_id)
                        ->update([
                            'deleted_at' => Carbon::now()
                        ]);

        if ($numberOfRows > 0)
            return $this->retrieve($comment_id, $post_id);

        return false;
    }

    protected function save_record($post_id, $commentator, $comment)
    {
        $commentId = DB::table('comments')->insertGetId([
            'post_id' => $post_id,
            'commentator' => ucfirst($commentator),
            'comment' => ucfirst($comment),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return $commentId;
    }

    public function retrieve()
    {
        return DB::table('comments')
                ->select('commentator', 'comment', 'created_at')
                ->where('id', $this->commentId)
                ->where('post_id', $this->postId)
                ->whereNull('deleted_at')
                ->first();
    }

    public function store_child($parentId, $childId, $post_id)
    {
        DB::table('parent_child_comments')->insert([
            'parent_comment_id' => $parentId,
            'child_comment_id' => $childId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return $this->retrieve($childId, $post_id);
    }

    public function retrieve_all_comments($post_id)
    {
        $response = DB::table('comments')
                        ->select('id', 'post_id', 'commentator', 'comment', 'has_parent', 'created_at')
                        ->where('post_id', $post_id)
                        ->where('has_parent', false)
                        ->whereNull('deleted_at')
                        ->get();

        return $this->get_replies($response);
    }

    protected function get_replies($comments)
    {
        $comments = collect($comments);
        return $comments->map(function ($comment) {
            $replies = $this->has_reply($comment->id);
            $comment->comments = $this->get_replies($replies);

            return $comment;
        });
    }

    protected function has_reply($id)
    {
        $response = DB::select(
            "SELECT id, commentator, comment, created_at FROM comments WHERE id IN (SELECT child_comment_id FROM parent_child_comments WHERE parent_comment_id = ? AND deleted_at IS NULL) ORDER BY created_at DESC",
            [$id]
        );

        return $response;
    }
}
