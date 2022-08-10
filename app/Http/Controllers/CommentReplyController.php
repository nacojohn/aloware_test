<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CommentReplyController extends ResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $post_id, $comment_id)
    {
        //request for all post values
        $input = [
            ...$request->all(),
            ...[
                'post_id' => $post_id,
                'comment_id' => $comment_id
            ]
        ];

        //Validate to ensure valid inputs
        $validator = Validator::make($input, [
            'post_id' => 'required|exists:posts,id',
            'comment_id' => 'required|exists:comments,id',
            'name' => 'required',
            'comment' => 'required'
        ]);

        //handle validation error
        if ($validator->fails())
            return $this->sendError('Validation error', $validator->errors()->all(), Response::HTTP_BAD_REQUEST);

        // check duplicate comment if need be

        // create the comment record
        $comment = new Comment();
        $id = $comment->storeGetId($post_id, $request->name, $request->comment);
        $data = $comment->store_child($comment_id, $id, $post_id);

        // return response
        return $this->sendResponse($data, 'Comment was successful', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
