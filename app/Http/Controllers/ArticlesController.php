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
        // 檢查使用者登入狀態，如果沒有先將uid設null，要不然會拋錯
        // 賦予uid為當前使用者之id
        $uid = Auth::check() ? auth()->user()->id : null;
        // 使用with()語句優化查詢，解決N+1問題，paginate()為laravel提供之分頁系統
        $articles = Article::with('user')->orderBy('id', 'desc')->paginate(3);

        // 遍歷所有文章，查看每一篇文章的作者是否為當前使用者
        foreach ($articles as $article) {
            // 如果文章與當前使用者有關聯，進行判斷
            if ($article->user) {
                $author_id = $article->user->id; // author_id=作者的id  
                $status = $author_id == $uid; // 判斷作者是否等於當前使用者
                $article->status = $status; // 將status 添加進去articles裡面
            } else {
                // 處理沒有關聯的時候
                return redirect('root')->with('notice', '出現意外錯誤');
            }
        }

        // 渲染前端，並將資料傳送給前端，傳送包括status在內的articles
        return view('articles.index', ['articles' => $articles]);
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
