<nav class="switch-navbar fixed-top">
  <div class="switch-navbar-inner">

    {{-- 上 60% --}}
    <div class="switch-navbar-top">
      <div class="container d-flex justify-content-between align-items-center">
        <div class="switch-brand">
          <span class="d-none d-md-inline" style="font-size:1.7rem;">Switch</a></span>
          <span class="d-inline d-md-none" style="font-size:1.3rem;">Switch</span>
        </div>

        {{-- 右：利用者名（md以上）＋ 設定ドロップダウン（md以上） --}}
        <div class="d-flex align-items-center gap-3">
          @auth('user')
            <span class="text-dark fw-bold d-none d-md-inline">
              利用者&nbsp;&#58;&nbsp;{{ optional(Auth::guard('user')->user())->name }}
            </span>

            {{-- 設定ギア（md以上で表示） --}}
            <div class="dropdown d-none d-md-block">
              <button class="btn btn-settings" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="設定メニュー">
                <i class="bi bi-gear"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow">
                <li>
                  <a class="dropdown-item d-flex align-items-center" href="{{ route('user.reset_password.form') }}">
                    <i class="bi bi-key me-2"></i> PW設定
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form method="POST" action="{{ route('user.logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                      <i class="bi bi-power me-2"></i> ログアウト
                    </button>
                  </form>
                </li>
              </ul>
            </div>
          @endauth
        </div>
      </div>
    </div>

    {{-- 下 40%：メニュー（認証後のみ表示／モバイルはハンバーガー、md以上は一行＆横スクロール可） --}}
    <div class="switch-navbar-bottom">
      <div class="container">
        @auth('user')
          {{-- モバイル：中央にハンバーガー --}}
          <div class="d-flex justify-content-center d-md-none">
            <button class="btn btn-menu-toggle d-flex align-items-center gap-2"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#switchBottomMenu"
                    aria-expanded="false"
                    aria-controls="switchBottomMenu">
              <i class="bi bi-list fs-4"></i><span>メニュー</span>
            </button>
          </div>

          {{-- メニュー本体：md以上は常時表示・1行固定、smではcollapse --}}
          <div id="switchBottomMenu" class="collapse d-md-block mt-2 mt-md-0">
            <ul class="nav nav-line justify-content-md-center align-items-center gap-2 flex-md-nowrap">
              {{-- モバイル時のみ利用者名も表示 --}}
              <li class="nav-item d-md-none">
                <span class="text-white fw-bold">
                  {{ optional(Auth::guard('user')->user())->name }}
                </span>
              </li>

              <li class="nav-item">
                <a class="nav-link nav-pill d-flex align-items-center" href="{{ route('user.home') }}">
                  <i class="bi bi-house-door me-1"></i><span>ホーム</span>
                </a>
              </li>
              @if(Auth::guard('user')->user()->has_custom_flow === 0)
                <li class="nav-item">
                    <a class="nav-link nav-pill d-flex align-items-center" href="{{ route('user.home') }}">
                    <i class="bi bi-speedometer2 me-1"></i><span>店舗全体</span>
                    </a>
                </li>
              @endif
              <li class="nav-item">
                <a class="nav-link nav-pill d-flex align-items-center" href="{{ route('user.home') }}">
                  <i class="bi bi-graph-up me-1"></i><span>貸玉毎全体</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link nav-pill d-flex align-items-center" href="{{ route('user.home') }}">
                <i class="bi bi-tools me-1"></i><span>貸玉機種毎</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link nav-pill d-flex align-items-center" href="{{ route('user.home') }}">
                    <i class="bi bi-cash-coin me-1"></i><span>機械購入分析</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link nav-pill d-flex align-items-center" href="{{ route('user.home') }}">
                  <i class="bi bi-cash-coin me-1"></i><span>固定費予測</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link nav-pill d-flex align-items-center" href="{{ route('user.home') }}">
                  <i class="bi bi-sliders me-1"></i><span>玉単価設定</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link nav-pill d-flex align-items-center" href="{{ route('user.home') }}">
                  <i class="bi bi-sliders2 me-1"></i><span>コイン単価設定</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link nav-pill d-flex align-items-center" href="{{ route('user.home') }}">
                  <i class="bi bi-sliders2 me-1"></i><span>機種タイプ設定</span>
                </a>
              </li>

              {{-- スマホ時のみ：ここにも PW設定 / ログアウトを出しておく（ハンバーガー内） --}}
              <li class="nav-item d-md-none">
                <a class="nav-link nav-pill d-flex align-items-center" href="{{ route('user.reset_password.form') }}">
                  <i class="bi bi-key me-1"></i><span>PW設定</span>
                </a>
              </li>
              <li class="nav-item d-md-none">
                <form method="POST" action="{{ route('user.logout') }}">
                  @csrf
                  <button type="submit" class="btn btn-logout nav-pill d-flex align-items-center w-100">
                    <i class="bi bi-power me-1"></i><span>ログアウト</span>
                  </button>
                </form>
              </li>
            </ul>
          </div>
        @endauth
      </div>
    </div>

  </div>
</nav>
