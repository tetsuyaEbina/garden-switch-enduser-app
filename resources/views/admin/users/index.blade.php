@extends('admin.app')
@section('title', 'ユーザー一覧')

@section('content')
@php
    $titleStr = $onlyTrashed === true ? '(削除済み)' : '(有効)';
@endphp
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>ユーザ一覧 {{ $titleStr }}</h2>
        <div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">
                &plus;&nbsp;新規作成
            </a>

            @if (!$onlyTrashed)
                <a class="btn btn-outline-secondary btn-sm active disabled" aria-disabled="true">
                    有効ユーザ
                </a>
            @else
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                    有効ユーザ
                </a>
            @endif

            @if ($onlyTrashed)
                <a class="btn btn-outline-secondary btn-sm active disabled" aria-disabled="true">
                    削除済ユーザ
                </a>
            @else
                <a href="{{ route('admin.users.index', ['trashed' => 1]) }}" class="btn btn-outline-secondary btn-sm">
                    削除済ユーザ
                </a>
            @endif
        </div>
    </div>
    <hr>

    {{-- メッセージ --}}
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

    {{-- 検索フォーム --}}
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-3">
        @if ($onlyTrashed)
            <input type="hidden" name="trashed" value="1">
        @endif
        <div class="input-group">
            <input type="text" name="keyword" class="form-control" placeholder="名前で検索" value="{{ request('keyword') }}">
            <button class="btn btn-outline-secondary">検索</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">リセット</a>
        </div>
    </form>


    {{-- ユーザー一覧 --}}
    <table class="table table-bordered">
        <thead class="bg-dark text-white text-center table-dark">
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>メール</th>
                <th>法人名</th>
                <th>登録日</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td class="text-end">{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->userCompany->user_company_name ?? '-' }}</td>
                    <td class="text-center">{{ $user->created_at->format('Y-m-d') }}</td>
                    <td class="text-center">
                        @if ($user->deleted_at)
                            <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('このユーザを復元しますか？');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-success">復元</button>
                            </form>
                        @else
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">編集</a>
                            <form action="{{ route('admin.users.reset_password', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('該当ユーザがログインしている場合、強制的にログアウトされます。\n初期パスワードにリセットしますか？');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-secondary">PW初期化</button>
                            </form>
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('該当ユーザがログインしている場合、強制的にログアウトされます。\nこのユーザーを削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">削除</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">該当するユーザは見つかりませんでした。</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ページネーション --}}
    @if ($paginate && method_exists($users, 'links'))
        <div class="mt-3">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
