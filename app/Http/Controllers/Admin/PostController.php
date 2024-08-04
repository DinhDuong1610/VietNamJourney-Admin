<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Services\PostService;

class PostController extends Controller
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function getPosts()
    {
        $posts = $this->postService->getPosts();

        return view('admin.pages.community.index_list', [
            'posts' => $posts
        ]);
    }

    public function getPendingPosts()
    {
        $posts = $this->postService->getPendingPosts();

        return view('admin.pages.community.index_pending', [
            'posts' => $posts
        ]);
    }

    public function deletePost($id)
    {
        $success = $this->postService->deletePost($id);

        if ($success) {
            return response()->json(['success' => true, 'message' => 'Bài viết đã được xóa.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Không thể xóa bài viết.']);
        }
    }

    public function approvePost($id)
    {
        $success = $this->postService->approvePost($id);

        if ($success) {
            return response()->json(['success' => true, 'message' => 'Bài viết đã được duyệt']);
        } else {
            return response()->json(['success' => false, 'message' => 'Không thể duyệt bài viết.']);
        }
    }
}
