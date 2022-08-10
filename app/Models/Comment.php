<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    use HasFactory;

    public function store($post_id, $commentator, $comment)
    {
        $commentId = $this->save_record($post_id, $commentator, $comment);

        return $this->retrieve($commentId, $post_id);
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

        if ($numberOfRows > 0)
            return $this->retrieve($comment_id, $post_id);

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

    public function retrieve($id, $post_id)
    {
        return DB::table('comments')
                ->select('commentator', 'comment', 'created_at')
                ->where('id', $id)
                ->where('post_id', $post_id)
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
}
