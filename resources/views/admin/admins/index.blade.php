@extends('admin.app')
@section('title', '管理者一覧')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Switch管理者一覧</h2>
        <a href="{{ route('admin.admins.create') }}" class="btn btn-sm btn-primary">&plus;&nbsp;新規作成</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr class="text-center">
                <th></th>
                <th>名前</th>
                <th>メール</th>
                <th>登録日</th>
                <th>is_root</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $admin)
                <tr>
                    <td class="text-end">{{ $admin->id }}</td>
                    <td class="text-start">{{ $admin->name }}</td>
                    <td class="text-start">{{ $admin->email }}</td>
                    <td class="text-center">{{ $admin->created_at->format('Y-m-d') }}</td>
                    <td class="text-center">{{ $admin->is_root }}</td>
                    <td class="text-center">
                        @if(Auth::guard('admin')->user()->is_root === 1)
                            <a href="{{ route('admin.admins.edit', $admin->id) }}" class="btn btn-sm btn-warning">編集</a>
                            <form action="{{ route('admin.admins.reset_password', $admin->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('パスワードを初期化しますか？');">
                                @csrf
                                <button class="btn btn-sm btn-secondary">PW初期化</button>
                            </form>
                            <form action="{{ route('admin.admins.delete', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('削除しますか？');">
                                @csrf
                                <button class="btn btn-sm btn-danger">削除</button>
                            </form>
                        @elseif(Auth::guard('admin')->user()->id === $admin->id)
                            <a href="{{ route('admin.admins.edit', $admin->id) }}" class="btn btn-sm btn-warning">編集</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
