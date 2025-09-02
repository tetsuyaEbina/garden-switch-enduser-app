@extends('admin.app')
@section('title', '管理者ログイン')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6" style="margin-top: 10%">
        <div class="card shadow-sm">
            <div class="card-header text-center fw-bold">管理者ログイン</div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                    {{ $errors->first() }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">メールアドレス</label>
                        <input type="email" name="email" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">パスワード</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">ログイン</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
