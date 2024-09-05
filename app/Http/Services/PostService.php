<?php

namespace App\Http\Services;

use App\Models\Post;

class PostService extends BaseCrudService
{
    private Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
        parent::__construct($post);
    }

    public function getPosts()
    {
        return $this->post
            ->where('status', 1)
            ->with('user.userInformation')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getPendingPosts()
    {
        return $this->post
            ->where(function ($query) {
                $query->where('status', 0)
                    ->orWhereNull('status');
            })
            ->with('user.userInformation')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function deletePost($id)
    {
        $post = $this->post->find($id);
        if ($post) {
            $post->status = 2;
            return $post->save();
        }
        return false;
    }

    public function approvePost($id)
    {
        $post = $this->post->find($id);
        if ($post) {
            $post->status = 1;
            return $post->save();
        }
        return false;
    }
}
