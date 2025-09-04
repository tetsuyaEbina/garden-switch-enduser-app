@extends('admin.app')
@section('title', '管理者トップページ')
@section('content')
<div class="container">
    {{-- 内容は未定：ログイン後のトップページ --}}
    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-1">
                    <i class="bi bi-file-earmark-text me-1"></i> ログビューア
                </h5>
                <p class="card-text mb-0 small text-muted">
                    アプリケーションログを確認できます。管理者のみアクセス可能です。
                </p>
            </div>
            <div>
                <a href="{{ url('/log-viewer') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-box-arrow-up-right me-1"></i>開く
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
