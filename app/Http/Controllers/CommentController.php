<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Article;
use App\Models\User;
use App\Notifications\NewCommentNotify;
use App\Jobs\VeryLongJob;


class CommentController extends Controller
{
    public function store(Request $request)
    {
        $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key'=>'comments*[0-9]'])->get();
        foreach($keys as $param) {
            Cache::forget($param->key);
        }

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
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        $comments = Cache::remember('comments'.$page, 3000, function() {
            return Comment::latest()->paginate(10);
        });
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
        $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key'=>'comments*[0-9]'])->get();
        foreach($keys as $param) {
            Cache::forget($param->key);
        }
        $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key'=>'comment_article'.$comment->article_id])->get();
        foreach($keys as $param) {
            Cache::forget($param->key);
        }

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
        Cache::flush();
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
        $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key'=>'comments*[0-9]'])->get();
        foreach($keys as $param) {
            Cache::forget($param->key);
        }
        $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key'=>'comment_article'.$comment->article_id])->get();
        foreach($keys as $param) {
            Cache::forget($param->key);
        }

        $users = User::where('id', '!=', $comment->user_id)->get();
        $article = Article::findOrfail($comment->article_id);
        $comment->accept = true;
        if ($comment->save()) Notification::send($users, new NewCommentNotify($article, $comment->name));
        return redirect()->route('comment.show');
    }

    public function reject(Comment $comment)
    {
        Cache::flush();
        $comment->accept = false;
        $comment->save();
        return redirect()->route('comment.show');
    }
}
