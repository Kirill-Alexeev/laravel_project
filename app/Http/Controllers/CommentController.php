<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|min:3',
            'desc' => 'required|max:255',
        ]);
        $comment = new Comment();
        $comment->name = request('name');
        $comment->desc = request('desc');
        $comment->article_id = request('article_id');
        $comment->user_id = 1;

        $comment->save();
        return redirect()->back();
    }

    public function edit($id) {
        $comment = Comment::findOrFail($id);
        return view('comments.update', ['comment' => $comment]);
    }

    public function update(Request $request, Comment $comment) {
        $request->validate([
            'name' => 'required|min:3',
            'desc' => 'required|max:255'
        ]);
        $comment->user_id = request('user_id');
        $comment->article_id = request('article_id');
        $comment->name = request('name');
        $comment->desc = request('desc');

        if ($comment->save()) {
            return redirect()->route('article.show', ['article' => $comment->article_id])->with('status', "Update successful!");
        } else {
            return redirect()->route('comment.update', ['comment' => $comment->id])->with('status', "Update don't successful");  // Redirect back with input and errors
        }
    }

    public function delete($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->delete()) {
            return redirect()->back()->with('status', "Delete successful!");
        } else {
            return redirect()->route('article.show', ['comment' => $comment->id])->with('status', "Delete don't successful");  // Redirect back with input and errors
        }
    }
}
