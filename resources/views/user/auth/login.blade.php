@extends('user.app')
@section('title', 'ログイン')
@push('styles')
    <link href="{{ asset('user/css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <form method="POST" action="{{ route('user.login') }}" novalidate>
        @csrf

        <div class="card shadow-sm mt-5">
          <div class="card-body">

            {{-- 見出し --}}
            <div class="login-heading">
              会員ログイン
            </div>

            <p class="mb-2">会員の方はこちらからログインをお願いします。</p>
            <p class="mb-3">
              <strong>※注意事項</strong><br>
              <span style="color:#2D2D2D">
                ユーザは個別に設定されており、複数人で使い回す行為は禁止しております。<br>
                不審なログイン行動&nbsp;&#40;複数端末でのログインなど&#41;&nbsp;を発見し次第、アカウントは即ロックさせていただきます。<br>
                また、<strong>システム内のデータの引き抜き・無断転載・不正取得等の行為</strong>も固く禁止します。
              </span>
            </p>

            @if ($errors->any())
              <p class="text-danger fw-bold text-center">ログイン情報に誤りがあります。</p>
            @endif
            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
              </div>
            @endif

            <div class="mb-3">
              <label class="form-label mb-1">ID&nbsp;&#40;メールアドレス&#41;&nbsp;&#58;</label>
              <input type="email" name="email" placeholder="メールアドレス"
                     class="form-control"
                     value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-4">
              <label class="form-label mb-1">パスワード&nbsp;&#58;</label>
              <div class="input-group">
                <input id="password" type="password" name="password" placeholder="パスワード"
                       class="form-control" required>
                <button class="btn btn-outline-secondary" type="button"
                        onclick="const p=document.getElementById('password');p.type=p.type==='password'?'text':'password'">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <div class="d-flex justify-content-center">
              <button type="submit" class="btn btn-switch w-100">送信</button>
            </div>

          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="row justify-content-center mt-4">
  <div class="col-md-8 col-lg-6">
    <div class="alert alert-info small">
      <strong>推奨環境</strong><br>
      本システムは以下の環境でのご利用を推奨しています。<br>
      &#149;PCブラウザ&nbsp;&#40;Google Chrome、Microsoft Edge、Safari の最新版&#41;&nbsp;<br>
      &#149;インターネット回線が安定した環境<br><br>
      &#42;スマートフォンやタブレットからの利用は動作保証外となります。<br>
      &#42;古いブラウザや Internet Explorer では正しく表示されません。
    </div>
  </div>
</div>
@endsection
