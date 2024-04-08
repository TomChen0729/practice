<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticlesController extends Controller
{
    //
    public function __construct(){
        // 透過中間件驗證使用者是否登入
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(){
        // 透過ORM向資料庫撈資料，並透過with()方法優化查詢語句
        // 越新的文章顯示在越前面
        // 使用內建的分頁系統
        $articles = Article::with('user')->orderBy('id', 'desc')->paginate(3);
        // $user_id = auth()->user()->articles()->id;
        // 渲染版面，並將撈出來的資料傳給前端
        return view('articles.index', ['articles' => $articles]);
        // return view('articles.index', ['articles' => $articles])->with($user_id);
    }
    public function show($id){
        // 在index點擊目標文章時，會帶目標作者id，並送到DB去撈該篇文章的內容
        $article = Article::find($id);
        // 抓出文章作者
        $user = $article->user->name;
        // 渲染版面，並將文章內容與作者名稱傳給前端
        return view('articles.show', ['article' => $article, 'user' => $user]);
    }
    public function create(){
        // 導向新增文章頁面
        return view('articles.create');
    }
    public function store(Request $request){
        // if (Auth::check())
        // 驗證輸入內容：
        // 條件一：標題不為空
        // 條件二：內文不得少於10個字
        $content = $request->validate([
            'title' => 'required',
            'content' => 'required|min:10',

        ]);

        // 以自己的身分新增文章，文章只屬於自己
        auth()->user()->articles()->create($content);
        // 導向articles.index，因為在路由那邊已將index命名為'root'，並帶有session訊息給前端
        return redirect()->route('root')->with('notice', '文章新增成功!');
    }

    public function edit($id){
        $article = auth()->user()->articles->find($id);
        if (!$article) {
            return redirect()->route('root')->with('notice', '您無權編輯該文章');
        }
        return view('articles.edit', ['article' => $article]);
    }

    public function update(Request $request, $id){
        $article = auth()->user()->articles->find($id);
        if (!$article) {
            return redirect()->route('root')->with('notice', '您無權編輯該文章');
        }
        $content = $request->validate([
            'title' => 'required',
            'content' => 'required|min:10',
    
        ]);
    
        $article->update($content);
        return redirect()->route('root')->with('notice', '文章修改成功!');
    }

    public function destroy($id){
        $article = auth()->user()->articles->find($id);
        if (!$article) {
            return redirect()->route('root')->with('notice', '您無權刪除該文章');
        }
        $article->delete();
        return redirect()->route('root')->with('notice', '文章刪除成功!');
    }

}
