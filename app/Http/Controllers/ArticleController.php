<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Models\Comment;
use App\Events\NewArticleEvent;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::latest()->paginate(6);
        return view('article.index', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('article.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', [self::class]);
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|string|min:5|max:255',
            'desc' => 'required|string'
        ]);

        $article = new Article();
        $article->date = $request->date;
        $article->name = $request->name;
        $article->desc = $request->desc;
        $article->user_id = 1;
        if ($article->save()) {
            NewArticleEvent::dispatch($article);
        }

        return redirect('/article');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $comments = Comment::where('article_id', $article->id)->where('accept', true)->get();
        $user = User::findOrFail($article->user_id);
        return view('article.show', ['article' => $article, 'user' => $user, 'comments' => $comments]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('article.update', ['article' => $article]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        Gate::authorize('update', $article->id);
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|string|min:5|max:255',
            'desc' => 'required|string'
        ]);

        $article->date = $request->date;
        $article->name = $request->name;
        $article->desc = $request->desc;
        $article->user_id = 1;


        if ($article->save()) {
            return redirect('/article')->with('status', "Update successful!");
        } else {
            return redirect()->route('article.edit', ['article' => $article->id])->with('status', "Update don't successful");  // Redirect back with input and errors
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        Gate::authorize('delete', [self::class]);
        if ($article->delete()) {
            return redirect('/article')->with('status', "Delete successful!");
        } else {
            return redirect()->route('article.show', ['article' => $article->id])->with('status', "Delete don't successful");  // Redirect back with input and errors
        }
    }
}
