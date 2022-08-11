<p align="center"><a href="" target="_blank"><img src="https://scontent.fbni1-2.fna.fbcdn.net/v/t1.6435-9/86495125_3117085231648661_8673856311638622208_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=09cbfe&_nc_eui2=AeEpJpk8NAwc1vmbiJrF-it0_pheX7u2K3_-mF5fu7Yrf4rf0JSyvwsUvae2-2slGdqGu-iEa1e4eIpqar1pZHco&_nc_ohc=DDwvGamz5fEAX-6tHpV&_nc_ht=scontent.fbni1-2.fna&oh=00_AT8vU8cuA4jJaZ7d1INaOJ6wRYT8B879gKa6FOpT-Yelhg&oe=63196590" width="400"></a></p>

## Table of Contents
- [About Nnanna](#https://github.com/nacojohn)
- [Response Format](#response-format)
- [Response Code](#response-codes)
- [Endpoints](#endpoints)
    1. [Create Comment](#create-comment)
    2. [Update Comment](#update-comment)
    3. [Delete Comment](#delete-comment)
    4. [Create Comment Reply](#create-comment-reply)
    5. [Update Comment Reply](#update-comment-reply)
    6. [Delete Comment Reply](#delete-comment-reply)
    7. [Retrieve Post Comment](#retrieve-post-comment)

## Response Format

For every successful request with a 200 or 201 response code, the response is formatted like this:

                {
                    "success": true (boolean),
                    "data": (null or an object),
                    "message": (string)
                }

For every failed request, the response is formatted like this:

                {
                    "success": false (boolean),
                    "message": (string),
                    "data": (null or an object)
                }

## Response Codes

200 - OK \
429 - Too much request, try again after 30 secs \
401 - Unauthorized \
400 - Bad Request \
422 - Unprocessed request due to invalid data

## Endpoints

URL: `http://127.0.0.1:8000` \
Suffix: `/api/`

#### Create Comment

- Endpoint: `{URL}+{Suffix}+'posts/{post_id}/comments'`
- Method: `POST`
- Body:

                {
                    'name' => 'required',
                    'comment' => 'required'
                }
- Response:

                {
                    "success": true,
                    "data": {
                        "commentator": "Igwe",
                        "comment": "A new comment by Igwe",
                        "created_at": "2022-08-11 10:37:11"
                    },
                    "message": "Comment was successful"
                }

#### Update Comment

- Endpoint: `{URL}+{Suffix}+'posts/{post_id}/comments/{comment_id}'`
- Method: `PATCH`
- Body:

                {
                    'name' => 'required',
                    'comment' => 'required'
                }
- Response:

                {
                    "success": true,
                    "data": {
                        "commentator": "Igwe",
                        "comment": "A new comment by Igwe_updated",
                        "created_at": "2022-08-11 10:37:11"
                    },
                    "message": "Comment updated successful"
                }

#### Delete Comment

- Endpoint: `{URL}+{Suffix}+'posts/{post_id}/comments/{comment_id}'`
- Method: `DELETE`
- Response:

                {
                    "success": true,
                    "data": "",
                    "message": "Comment deleted successful"
                }

#### Create Comment Reply

- Endpoint: `{URL}+{Suffix}+'posts/{post_id}/comments/{comment_id}/replies'`
- Method: `POST`
- Body:

                {
                    'name' => 'required',
                    'comment' => 'required'
                }
- Response:

                {
                    "success": true,
                    "data": {
                        "commentator": "Peter",
                        "comment": "A new comment by Peter",
                        "created_at": "2022-08-11 11:07:50"
                    },
                    "message": "Comment was successful"
                }

#### Update Comment Reply

- Endpoint: `{URL}+{Suffix}+'posts/{post_id}/comments/{comment_id}/replies/{reply_id}'`
- Method: `PATCH`
- Body:

                {
                    'name' => 'required',
                    'comment' => 'required'
                }
- Response:

                {
                    "success": true,
                    "data": {
                        "commentator": "Peter",
                        "comment": "A new comment by Peter_update",
                        "created_at": "2022-08-11 11:07:50"
                    },
                    "message": "Comment updated successful"
                }

#### Delete Comment Reply

- Endpoint: `{URL}+{Suffix}+'posts/{post_id}/comments/{comment_id}/replies/{reply_id}'`
- Method: `DELETE`
- Response:

                {
                    "success": true,
                    "data": "",
                    "message": "Comment deleted successful"
                }

#### Retrieve Post Comment

- Endpoint: `{URL}+{Suffix}+'posts/{post_id}/comments'`
- Method: `GET`
- Response:

                {
                    "success": true,
                    "data": [
                        {
                            "id": 1,
                            "post_id": 1,
                            "commentator": "Commentator 2_update",
                            "comment": "The new 2 comment Nam rhoncus, nulla in auctor venenatis, ipsum libero ullamcorper tortor, ac eleifend massa augue nec libero. Nulla facilisi.",
                            "has_parent": 0,
                            "created_at": null,
                            "comments": [
                                {
                                    "id": 6,
                                    "commentator": "Nacojohn_u",
                                    "comment": "Try again ipsum libero ullamcorper tortor, ac eleifend massa augue nec libero. Nulla facilisi.",
                                    "created_at": "2022-08-10 20:45:49",
                                    "comments": [
                                        {
                                            "id": 7,
                                            "commentator": "Nacojohn",
                                            "comment": "Try again ipsum libero ullamcorper tortor, ac eleifend massa augue nec libero. Nulla facilisi.",
                                            "created_at": "2022-08-10 20:46:28",
                                            "comments": []
                                        }
                                    ]
                                },
                                {
                                    "id": 5,
                                    "commentator": "Commentator 2",
                                    "comment": "The new 2 comment Nam rhoncus, nulla in auctor venenatis, ipsum libero ullamcorper tortor, ac eleifend massa augue nec libero. Nulla facilisi.",
                                    "created_at": "2022-08-10 14:51:11",
                                    "comments": []
                                }
                            ]
                        },
                        {
                            "id": 2,
                            "post_id": 1,
                            "commentator": "Commentator 1",
                            "comment": "Nam rhoncus, nulla in auctor venenatis, ipsum libero ullamcorper tortor, ac eleifend massa augue nec libero. Nulla facilisi.",
                            "has_parent": 0,
                            "created_at": "2022-08-10 13:45:13",
                            "comments": []
                        },
                        {
                            "id": 3,
                            "post_id": 1,
                            "commentator": "Commentator 2",
                            "comment": "The new comment Nam rhoncus, nulla in auctor venenatis, ipsum libero ullamcorper tortor, ac eleifend massa augue nec libero. Nulla facilisi.",
                            "has_parent": 0,
                            "created_at": "2022-08-10 13:46:40",
                            "comments": []
                        }
                    ],
                    "message": "Comments retrieved successful"
                }

