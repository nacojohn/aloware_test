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
        echo 'return the comment reply';
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
        $data = $comment->store_reply($post_id, $comment_id, $request->name, $request->comment)->retrieve();

        // return response
        return $this->sendResponse($data, 'Comment was successful', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($post_id, $comment_id, $reply_id)
    {
        //request for all post values
        $input = [
            'comment_id' => $reply_id
        ];

        //Validate to ensure valid inputs
        $validator = Validator::make($input, [
            'comment_id' => 'required|exists:comments,id'
        ]);

        //handle validation error
        if ($validator->fails())
            return $this->sendError('Validation error', $validator->errors()->all(), Response::HTTP_BAD_REQUEST);

        $data = (new Comment)->retrieve($reply_id, $post_id);

        // return response
        return $this->sendResponse($data, 'Comment retrieved successful');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $post_id, $comment_id, $id)
    {
        //request for all post values
        $input = [
            ...$request->all(),
            ...['comment_id' => $id]
        ];

        //Validate to ensure valid inputs
        $validator = Validator::make($input, [
            'comment_id' => 'required|exists:comments,id',
            'name' => 'required',
            'comment' => 'required'
        ]);

        //handle validation error
        if ($validator->fails())
            return $this->sendError('Validation error', $validator->errors()->all(), Response::HTTP_BAD_REQUEST);

        $comment = new Comment();
        $data = $comment->update_record($id, $post_id, $request->name, $request->comment)->retrieve();

        if (!$data)
            return $this->sendError('Request failed', 'Update could not be completed', Response::HTTP_BAD_REQUEST);

        // return response
        return $this->sendResponse($data, 'Comment updated successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($post_id, $comment_id, $id)
    {
        //request for all post values
        $input = [
            'comment_id' => $id
        ];

        //Validate to ensure valid inputs
        $validator = Validator::make($input, [
            'comment_id' => 'required|exists:comments,id'
        ]);

        //handle validation error
        if ($validator->fails())
            return $this->sendError('Validation error', $validator->errors()->all(), Response::HTTP_BAD_REQUEST);

        (new Comment)->delete_record($id, $post_id);

        // return response
        return $this->sendResponse('', 'Comment deleted successful');
    }
}
