@extends('layouts.article')
@section('title',  $article->user->name . '的文章')
@section('main')
    <h1 class="font-thin text-4xl">{{ $article->title }}</h1>
    <p class="text-lg text-gray-700 p-2">
        {{ $article->content }}
    </p>

    <div class="actions">
            <button type="reset" class="px-3 py-1 rouded bg-gray-200 hover:bg-gray-300"><a href="{{ route('articles.index') }}">回到文章列表</a></button>
        </div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // 3秒後自動隱藏
        setTimeout(function() {
            $("#alert").fadeOut("slow");
        }, 3000);
    });
</script>
@endsection
