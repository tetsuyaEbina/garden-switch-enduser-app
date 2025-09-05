@extends('admin.app')
@section('title', 'ユーザー新規登録')

@section('content')
<div class="container">
    <h2 class="mb-4">ユーザー新規登録</h2>
    <hr>

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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">氏名&nbsp;<span class="text-danger">&#42;</span></label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">メールアドレス&nbsp;<span class="text-danger">&#42;</span></label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">パスワード</label>
            <div class="form-text" style="margin-left: 10px">
                初期パスワード&nbsp;&colon;&nbsp;<code>{{ $initialPassword }}</code> が自動設定されます
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">法人&#40;任意&#41;</label>
            <select name="user_company_id" id="company-select" class="form-select">
                <option value="">個人利用</option>
                @foreach ($userCompanies as $company)
                    <option value="{{ $company->user_company_id }}">{{ $company->user_company_name }}</option>
                @endforeach
            </select>
        </div>

        <div id="company-fields" style="display: none;">
            <div class="mb-3">
                <label class="form-label">部署名</label>
                <input type="text" name="department_name" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">役職名</label>
                <input type="text" name="position_name" class="form-control">
            </div>
        </div>

        <div id="personal-fields">
            <div class="mb-3">
                <label class="form-label">インボイス番号</label>
                <input type="text" name="personal_invoice_number" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">請求先住所</label>
                <input type="text" name="personal_address" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">閲覧可能ホール</label>
            <div id="hall-list-wrapper">
                <div class="hall-select-group mb-2 d-flex align-items-start gap-2">
                    <div class="flex-grow-1">
                        <input type="text" class="form-control mb-1 hall-search" placeholder="ホール名を検索">
                        <select name="viewable_hall_id_list[]" class="form-select hall-select">
                            <option value="">未選択</option>
                            @foreach ($halls as $hall)
                                <option value="{{ $hall->hall_id }}">{{ $hall->hall_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-hall-btn">削除</button>
                </div>
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="add-hall-btn">&plus;&nbsp;追加</button>
        </div>

        <div class="mb-3">
            <label class="form-label d-flex align-items-center">
                カスタマイズUI
                <i class="bi bi-question-circle-fill text-primary ms-2" role="button" data-bs-toggle="modal" data-bs-target="#customFlowHelpModal"></i>
            </label>
            <select name="has_custom_flow" class="form-select">
                <option value="0" selected>いいえ</option>
                <option value="1">はい</option>
            </select>
        </div>

        <!-- カスタマイズUIの説明モーダル -->
        <div class="modal fade" id="customFlowHelpModal" tabindex="-1" aria-labelledby="customFlowHelpModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="customFlowHelpModalLabel">カスタマイズUIについて</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                    </div>
                    <div class="modal-body">
                        <p>このオプションは、<strong>NIKI専用UI</strong>を有効にするかどうかを指定します。</p>
                        <p><code>はい</code>を選択すると、NIKI向けにカスタマイズされた画面構成や機能が有効になります。</p>
                        <p>その他の利用者や法人は、<code>いいえ</code>のままで問題ありません。</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">閉じる</button>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-success btn-sm">登録</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">戻る</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('company-select').addEventListener('change', function () {
        const isCompany = this.value !== '';
        document.getElementById('company-fields').style.display = isCompany ? '' : 'none';
        document.getElementById('personal-fields').style.display = isCompany ? 'none' : '';
    });

    document.getElementById('add-hall-btn').addEventListener('click', function () {
        const container = document.getElementById('hall-list-wrapper');
        const html = `
            <div class="hall-select-group mb-2 d-flex align-items-start gap-2">
                <div class="flex-grow-1">
                    <input type="text" class="form-control mb-1 hall-search" placeholder="ホール名を検索">
                    <select name="viewable_hall_id_list[]" class="form-select hall-select">
                        <option value="">未選択</option>
                        @foreach ($halls as $hall)
                            <option value="{{ $hall->hall_id }}">{{ $hall->hall_name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm remove-hall-btn">削除</button>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
    });

    // ホール検索
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('hall-search')) {
            const keyword = e.target.value.toLowerCase();
            const select = e.target.nextElementSibling;
            Array.from(select.options).forEach(opt => {
                const text = opt.textContent.toLowerCase();
                opt.style.display = text.includes(keyword) ? '' : 'none';
            });
        }
    });

    // ホール選択フォーム削除
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-hall-btn')) {
            const group = e.target.closest('.hall-select-group');
            if (group) group.remove();
        }
    });
</script>
@endpush