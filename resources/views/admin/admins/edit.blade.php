@extends('admin.app')
@section('title', '管理者編集')

@section('content')
<div class="container">
    <h2 class="mb-4">Switch管理者編集</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">名前</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $admin->name) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">メールアドレス</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email', $admin->email) }}">
        </div>
        <button class="btn btn-sm btn-primary">更新</button>
        <a href="{{ route('admin.admins.index') }}" class="btn btn-sm btn-secondary">戻る</a>
    </form>
</div>
@endsection
