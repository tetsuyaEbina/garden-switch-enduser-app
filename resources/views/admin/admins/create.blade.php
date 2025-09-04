@extends('admin.app')
@section('title', '管理者新規作成')

@section('content')
<div class="container">
    <h2 class="mb-4">Switch管理者作成</h2>
    <hr>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.admins.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">名前</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">メールアドレス</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">パスワード</label>
            <div class="form-text" style="margin-left: 10px">
                初期パスワード&nbsp;&colon;&nbsp;<code>{{ $initialPassword }}</code> が自動設定されます
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">root権限</label>
            <div class="form-text" style="margin-left: 10px">
                root権限は付与されません。<br>
                is_root&nbsp;&colon;&nbsp;<code>0</code> が自動設定されます
            </div>
        </div>
        <button class="btn btn-sm btn-success">登録</button>
        <a href="{{ route('admin.admins.index') }}" class="btn btn-sm btn-secondary">戻る</a>
    </form>
</div>
@endsection
