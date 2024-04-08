@extends('layouts.article')
@section('title', '文章列表')
@section('main')
    @if(session('notice'))
        <div id="alert" class="alert alert-success">
            {{ session('notice') }}
        </div>
    @endif
    <h1 class="font-thin text-4xl">文章列表</h1>
    <a href="{{ route('articles.create') }}">新增文章</a>

    @foreach($articles as $article)
    <div class="border-t border-gray-300 my-1 p-2" style="border: 1px black solid;">
        <h2 class="font-bold text-lg"><a href="{{ route('articles.show', $article) }}">{{ $article -> title }}</a></h2>
        <p>
            {{ $article -> created_at }} 由 {{ $article->user->name }} 分享
        </p>
        <p>UID = {{ $article -> user_id }}</p>
        @if($article->status)
        <div class="flex" style="display: flex;">
            <button><a href="{{ route('articles.edit', $article)}}">編輯</a></button>
            <!-- <a href="{{ route('articles.destroy', $article)}}">刪除</a> -->
            <form  style="padding: 10px;" action="{{ route('articles.destroy', $article) }}" method="post">
                @csrf 
                @method('delete')
                <button type="submit" class="px-2 bg-red-500 text-red-100">刪除文章</button>
            </form>
        </div>
        @endif
    </div>
    @endforeach
    {{ $articles -> links() }}
    <button><a href="{{ route('dashboard') }}">回主畫面</a></button>
@endsection

@section('script')
<script>
    $().ready(function() {
        // 3秒後自動隱藏
        setTimeout(function() {
            $("#alert").fadeOut("slow");
        }, 3000);
    });
</script>
@endsection
