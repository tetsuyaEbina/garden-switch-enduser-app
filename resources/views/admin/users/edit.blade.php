@extends('admin.app')
@section('title', 'ユーザー編集')

@section('content')
<div class="container">
    <h2 class="mb-4">ユーザー編集</h2>
    <hr>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
        </div>
    @endif

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

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">氏名&nbsp;<span class="text-danger">&#42;</span></label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">メールアドレス&nbsp;<span class="text-danger">&#42;</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">法人&#40;任意&#41;</label>
            <select name="user_company_id" class="form-select user-company-select">
                <option value="">個人利用</option>
                @foreach ($userCompanies as $company)
                    <option value="{{ $company->user_company_id }}" {{ old('user_company_id', $user->user_company_id) == $company->user_company_id ? 'selected' : '' }}>
                        {{ $company->user_company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 personal-field {{ $user->user_company_id ? 'd-none' : '' }}">
            <label class="form-label">インボイス番号</label>
            <input type="text" name="personal_invoice_number" value="{{ old('personal_invoice_number', $user->personal_invoice_number) }}" class="form-control">
        </div>

        <div class="mb-3 personal-field {{ $user->user_company_id ? 'd-none' : '' }}">
            <label class="form-label">請求先住所</label>
            <input type="text" name="personal_address" value="{{ old('personal_address', $user->personal_address) }}" class="form-control">
        </div>

        <div class="mb-3 company-field {{ $user->user_company_id ? '' : 'd-none' }}">
            <label class="form-label">所属部署</label>
            <input type="text" name="department_name" value="{{ old('department_name', $user->department_name) }}" class="form-control">
        </div>

        <div class="mb-3 company-field {{ $user->user_company_id ? '' : 'd-none' }}">
            <label class="form-label">役職</label>
            <input type="text" name="position_name" value="{{ old('position_name', $user->position_name) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">閲覧可能ホール</label>
            <div id="hall-list-container">
                @foreach ($user->viewable_hall_id_list ?? [] as $index => $hallId)
                    <div class="d-flex mb-2 hall-entry">
                        <input type="text" class="form-control hall-search me-2" placeholder="ホール名で検索">
                        <select name="viewable_hall_id_list[]" class="form-select hall-select">
                            <option value="">未選択</option>
                            @foreach ($halls as $hall)
                                <option value="{{ $hall->hall_id }}" {{ $hall->hall_id == $hallId ? 'selected' : '' }}>
                                    {{ $hall->hall_name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-sm btn-outline-danger ms-2 remove-hall-btn">削除</button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="add-hall-btn">&plus;&nbsp;追加</button>
        </div>

        <div class="mb-3">
            <label class="form-label">カスタマイズUI
                <span role="button" data-bs-toggle="modal" data-bs-target="#customFlowModal" class="ms-1 text-primary" title="説明">
                    <i class="bi bi bi-question-circle-fill"></i>
                </span>
            </label>
            <select name="has_custom_flow" class="form-select">
                <option value="0" {{ old('has_custom_flow', $user->has_custom_flow) == 0 ? 'selected' : '' }}>いいえ</option>
                <option value="1" {{ old('has_custom_flow', $user->has_custom_flow) == 1 ? 'selected' : '' }}>はい</option>
            </select>
        </div>

        <!-- カスタマイズUIの説明モーダル -->
        <div class="modal fade" id="customFlowModal" tabindex="-1" aria-labelledby="customFlowHelpModalLabel" aria-hidden="true">
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

        <button type="submit" class="btn btn-sm btn-success">更新</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">戻る</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.user-company-select').forEach(select => {
        select.addEventListener('change', function () {
            const isCompany = this.value !== '';
            document.querySelectorAll('.company-field').forEach(el => el.classList.toggle('d-none', !isCompany));
            document.querySelectorAll('.personal-field').forEach(el => el.classList.toggle('d-none', isCompany));
        });
    });

    document.getElementById('add-hall-btn').addEventListener('click', function () {
        const container = document.getElementById('hall-list-container');
        const html = `
            <div class="d-flex mb-2 hall-entry">
                <input type="text" class="form-control hall-search me-2" placeholder="ホール名で検索">
                <select name="viewable_hall_id_list[]" class="form-select hall-select">
                    <option value="">未選択</option>
                    @foreach ($halls as $hall)
                        <option value="{{ $hall->hall_id }}">{{ $hall->hall_name }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-sm btn-outline-danger ms-2 remove-hall-btn">削除</button>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
    });

    // 削除
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-hall-btn')) {
            const entry = e.target.closest('.hall-entry');
            if (entry) entry.remove();
        }
    });

    // 検索フィルタ
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('hall-search')) {
            const keyword = e.target.value.toLowerCase();
            const select = e.target.nextElementSibling;
            if (!select) return;
            select.querySelectorAll('option').forEach(opt => {
                opt.style.display = opt.textContent.toLowerCase().includes(keyword) ? '' : 'none';
            });
        }
    });
</script>
@endpush
