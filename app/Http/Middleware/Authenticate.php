<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Models\Article;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    // public function handle($request, Closure $next, ...$guards){
    //     $articleId = $request->route('article'); // Assuming your route parameter name is 'article'
    //     $article = Article::find($articleId);

    //     if (!$article) {
    //         abort(404); // Article not found
    //     }

    //     // Check if the current user is the author of the article
    //     if ($article->user_id !== User::id()) {
    //         abort(403, 'Unauthorized action.'); // User is not authorized to perform this action
    //     }

    //     return $next($request);
    // }
}
