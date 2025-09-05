@extends('user.app')
@section('title', 'パスワード再設定')

@push('styles')
    <link href="{{ asset('user/css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <form method="POST" action="{{ route('user.reset_password') }}">
        @csrf

        <div class="card shadow-sm mt-5">
          <div class="card-body">

            {{-- 見出し --}}
            <div class="login-heading">
              パスワード再設定
            </div>

            <p class="mb-3">現在のパスワードを入力し、新しいパスワードを設定してください。</p>

            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            {{-- 現在のPW --}}
            <div class="mb-3">
                <label class="form-label">現在のパスワード&nbsp;&#58;</label>
                <div class="input-group">
                    <input id="current_password" type="password" name="current_password" class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('current_password', this)">
                    <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            {{-- 新規PW --}}
            <div class="mb-3">
                <label class="form-label">新しいパスワード&nbsp;&#58;</label>
                <div class="input-group">
                    <input id="new_password" type="password" name="new_password" class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('new_password', this)">
                    <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            {{-- 新規PW再確認 --}}
            <div class="mb-4">
                <label class="form-label">新しいパスワード&nbsp;&#40;確認&#41;&nbsp;&#58;</label>
                <div class="input-group">
                    <input id="new_password_confirmation" type="password" name="new_password_confirmation" class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('new_password_confirmation', this)">
                    <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-center">
              <button type="submit" class="btn btn-switch w-100">変更する</button>
            </div>

          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('bi-eye');
      icon.classList.add('bi-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('bi-eye-slash');
      icon.classList.add('bi-eye');
    }
  }
</script>
@endpush
