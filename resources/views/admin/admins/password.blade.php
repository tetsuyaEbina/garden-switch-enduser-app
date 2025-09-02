@extends('admin.app')
@section('title', 'パスワード変更')

@section('content')
<div class="container">
    <h2 class="mb-4">パスワード変更</h2>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
        </div>
    @endif

    <form action="{{ route('admin.admins.password.update') }}" method="POST">
        @csrf

        @foreach ([
            'current_password' => '現在のパスワード',
            'new_password' => '新しいパスワード',
            'new_password_confirmation' => '新しいパスワード（確認）'
        ] as $field => $label)
        <div class="mb-3 position-relative">
            <label class="form-label">{{ $label }}</label>
            <input type="password" name="{{ $field }}" class="form-control password-toggle" id="{{ $field }}" required>
            <span class="position-absolute top-50 end-0 translate-middle-y pe-3" style="cursor: pointer;" onclick="togglePassword('{{ $field }}')">
                <i class="bi bi-eye-slash" id="icon-{{ $field }}"></i>
            </span>
        </div>
        @endforeach

        <button class="btn btn-sm btn-primary">変更</button>
        <a href="{{ route('admin.home') }}" class="btn btn-sm btn-secondary">戻る</a>
    </form>
</div>

{{-- パスワード表示切替用スクリプト --}}
<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }
</script>
@endsection
