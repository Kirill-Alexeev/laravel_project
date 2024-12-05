<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use App\Jobs\VeryLongJob;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $article = Article::findOrFail($request->article_id);
        $request->validate([
            'name' => 'required|min:3',
            'desc' => 'required|max:255',
        ]);
        $comment = new Comment();
        $comment->name = request('name');
        $comment->desc = request('desc');
        $comment->article_id = request('article_id');
        $comment->user_id = Auth::id();

        if ($comment->save()) {
            VeryLongJob::dispatch($comment, $article->name);
            return redirect()->back()->with('status', 'Комментарий сохранен и отправлен на модерацию');
        }
    }

    public function show()
    {
        $comments = Comment::latest()->paginate(10);
        return view('comments.show', ['comments' => $comments]);
    }

    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        Gate::authorize('update_comment', $comment);
        return view('comments.update', ['comment' => $comment]);
    }

    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update_comment', $comment);
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
        Gate::authorize('update_comment', $comment);
        if ($comment->delete()) {
            return redirect()->back()->with('status', "Delete successful!");
        } else {
            return redirect()->route('article.show', ['comment' => $comment->id])->with('status', "Delete don't successful");  // Redirect back with input and errors
        }
    }

    public function accept(Comment $comment)
    {
        $comment->accept = true;
        $comment->save();
        return redirect()->route('comment.show');
    }

    public function reject(Comment $comment)
    {
        $comment->accept = false;
        $comment->save();
        return redirect()->route('comment.show');
    }
}
