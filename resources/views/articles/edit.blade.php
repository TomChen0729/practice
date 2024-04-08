@extends('layouts.article')
@section('title', '編輯文章')
@section('main')
    <h1 class="font-thin text-4xl">文章 > 編輯文章</h1>
    @if($errors->any())
        <div class="errors p-3 bg-red-500 text-red-100 font-thin rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('articles.update', $article) }}" method="post">
    @csrf
    @method('patch')
        <div class="field" style="padding: 10px;">
            <label for="">標題</label>
            <input type="text" value="{{ $article -> title }}" name="title" class="border border-gray-300 p-2">
        </div>
        <div class="field" style="padding: 10px;">
            <label for="">內文</label>
            <textarea name="content" id="" cols="30" rows="10" class="border border-gray-300 p-2">{{ $article -> content }}</textarea>
        </div>

        <div class="actions">
            <button type="submit" class="px-3 py-1 rouded bg-gray-200 hover:bg-gray-300">更新文章</button>
            <button type="reset" class="px-3 py-1 rouded bg-gray-200 hover:bg-gray-300">重來</button>
            <button type="reset" class="px-3 py-1 rouded bg-gray-200 hover:bg-gray-300"><a href="{{ route('articles.index') }}">回到文章列表</a></button>
        </div>
    </form>
@endsection