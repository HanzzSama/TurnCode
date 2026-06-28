<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonDiscussion;
use Illuminate\Http\Request;

class LessonDiscussionController extends Controller
{
    /**
     * Store a new discussion or reply.
     */
    public function store(Request $request, Lesson $lesson)
    {
        $request->validate([
            'content' => 'required|string|min:2|max:1000',
            'parent_id' => 'nullable|exists:lesson_discussions,id',
        ]);

        $discussion = LessonDiscussion::create([
            'lesson_id' => $lesson->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'likes' => [],
        ]);

        // Load the user to display user information in response
        $discussion->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dikirim.',
            'discussion' => [
                'id' => $discussion->id,
                'parent_id' => $discussion->parent_id,
                'content' => nl2br(e($discussion->content)),
                'user_name' => $discussion->user->name,
                'user_initials' => urlencode($discussion->user->name),
                'created_at_human' => $discussion->created_at->diffForHumans(),
                'likes_count' => 0,
                'is_liked' => false,
            ]
        ]);
    }

    /**
     * Toggle upvote/like state on a discussion.
     */
    public function toggleLike(LessonDiscussion $discussion)
    {
        $userId = auth()->id();
        $likes = $discussion->likes ?? [];

        if (in_array($userId, $likes)) {
            // Remove user ID from array
            $likes = array_values(array_diff($likes, [$userId]));
            $liked = false;
        } else {
            // Add user ID to array
            $likes[] = $userId;
            $liked = true;
        }

        $discussion->update(['likes' => $likes]);

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => count($likes)
        ]);
    }
}
