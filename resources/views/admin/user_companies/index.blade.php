@extends('admin.app')
@section('title', '法人一覧')

@section('content')
@php
    $titleStr = $onlyTrashed === true ? '(削除済み)' : '(有効)'
@endphp
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>法人一覧{{$titleStr}}</h2>
        <div>
            <a href="{{ route('admin.user_companies.create') }}" class="btn btn-sm btn-primary">
                &plus;&nbsp;新規作成
            </a>

            @if (!$onlyTrashed)
                <a class="btn btn-outline-secondary btn-sm active disabled" aria-disabled="true">
                    有効法人
                </a>
            @else
                <a href="{{ route('admin.user_companies.index') }}" class="btn btn-outline-secondary btn-sm">
                    有効法人
                </a>
            @endif

            @if ($onlyTrashed)
                <a class="btn btn-outline-secondary btn-sm active disabled" aria-disabled="true">
                    削除済法人
                </a>
            @else
                <a href="{{ route('admin.user_companies.index', ['trashed' => 1]) }}" class="btn btn-outline-secondary btn-sm">
                    削除済法人
                </a>
            @endif
        </div>
    </div>
    <hr>

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

    <form method="GET" action="{{ route('admin.user_companies.index') }}" class="mb-3">
        @if ($onlyTrashed)
            <input type="hidden" name="trashed" value="1">
        @endif
        <div class="input-group">
            <input type="text" name="keyword" class="form-control" placeholder="法人名で検索" value="{{ request('keyword') }}">
            <button class="btn btn-outline-secondary">検索</button>
            <a href="{{ route('admin.user_companies.index') }}" class="btn btn-outline-secondary">全リセット</a>
        </div>
        <small class="text-muted">&#42;法人名絞り込み検索は、全データに対して実行されます&#40;ページネーション外&#41;</small>
    </form>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr class="text-center">
                <th>ID</th>
                <th>法人名</th>
                <th>法人番号</th>
                <th>インボイス番号</th>
                <th>所属ユーザ数</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($userCompanies as $company)
                <tr>
                    <td class="text-end">{{ $company->user_company_id }}</td>
                    <td class="text-start">{{ $company->user_company_name }}</td>
                    <td class="text-center">{{ $company->corporate_number ?? '-' }}</td>
                    <td class="text-center">{{ $company->invoice_number ?? '-' }}</td>
                    <td class="text-center">{{ $company->users_count }}</td>
                    <td class="text-center">
                        @if ($company->deleted_at)
                            <form action="{{ route('admin.user_companies.restore', $company->id) }}" method="POST" class="d-inline" onsubmit="return confirm('所属ユーザも全て利用できるようになります。復元しますか？');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-success">復元</button>
                            </form>
                        @else
                            <a href="{{ route('admin.user_companies.edit', $company->id) }}" class="btn btn-sm btn-warning">編集</a>
                            <form action="{{ route('admin.user_companies.delete', $company->id) }}" method="POST" class="d-inline" onsubmit="return confirm('所属ユーザも全て利用できなくなります。削除しますか？');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">削除</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">該当する法人は見つかりませんでした。</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($paginate && method_exists($userCompanies, 'links'))
        <div class="mt-3">
            {{ $userCompanies->links() }}
        </div>
    @endif
</div>
@endsection
