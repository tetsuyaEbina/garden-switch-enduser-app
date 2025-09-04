@extends('admin.app')
@section('title', '法人一括登録')

@section('content')
<div class="container">
    <h2 class="mb-4">法人一括登録&#40;最大5件&#41;</h2>
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

    <form action="{{ route('admin.user_companies.store') }}" method="POST" id="companyForm">
        @csrf

        <div id="company-forms-container">
            {{-- 初期1行 --}}
            <div class="border rounded p-3 mb-3 company-form-row">
                <h5 class="mb-3">法人 1</h5>
                <div class="mb-2">
                    <label class="form-label">法人ID&nbsp;<span class="text-danger">&#42;</span></label>
                    <input type="number" name="companies[0][user_company_id]" class="form-control" value="{{ old('companies.0.user_company_id') }}">
                    <small class="text-muted"><span class="text-danger">&#42;法人IDは一度設定すると、原則後から変更はできません。</span></small>
                </div>
                <div class="mb-2">
                    <label class="form-label">法人名&nbsp;<span class="text-danger">&#42;</span></label>
                    <input type="text" name="companies[0][user_company_name]" class="form-control" value="{{ old('companies.0.user_company_name') }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">法人番号&#40;13桁&#41;</label>
                    <input type="text" name="companies[0][corporate_number]" class="form-control" value="{{ old('companies.0.corporate_number') }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">インボイス番号&#40;T&plus;数字13桁&#41;</label>
                    <input type="text" name="companies[0][invoice_number]" class="form-control" value="{{ old('companies.0.invoice_number') }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">住所</label>
                    <input type="text" name="companies[0][address]" class="form-control" value="{{ old('companies.0.address') }}">
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-sm btn-outline-secondary mb-3" id="addRowBtn">＋追加</button>

        <div>
            <button class="btn btn-sm btn-success">登録</button>
            <a href="{{ route('admin.user_companies.index') }}" class="btn btn-sm btn-secondary">戻る</a>
        </div>
    </form>
</div>

<script>
    let companyIndex = 1;

    document.getElementById('addRowBtn').addEventListener('click', function () {
        if (companyIndex >= 5) {
            alert('最大5件まで登録できます。');
            return;
        }

        const container = document.getElementById('company-forms-container');

        const html = `
        <div class="border rounded p-3 mb-3 company-form-row">
            <h5 class="mb-3">法人 ${companyIndex + 1}</h5>
            <div class="mb-2">
                <label class="form-label">法人ID&nbsp;<span class="text-danger">&#42;</span></label>
                <input type="number" name="companies[${companyIndex}][user_company_id]" class="form-control">
                <small class="text-muted"><span class="text-danger">&#42;法人IDは一度設定すると、原則後から変更はできません。</span></small>
            </div>
            <div class="mb-2">
                <label class="form-label">法人名&nbsp;<span class="text-danger">&#42;</span></label>
                <input type="text" name="companies[${companyIndex}][user_company_name]" class="form-control">
            </div>
            <div class="mb-2">
                <label class="form-label">法人番号&#40;13桁&#41;</label>
                <input type="text" name="companies[${companyIndex}][corporate_number]" class="form-control">
            </div>
            <div class="mb-2">
                <label class="form-label">インボイス番号&#40;T&plus;数字13桁&#41;</label>
                <input type="text" name="companies[${companyIndex}][invoice_number]" class="form-control">
            </div>
            <div class="mb-2">
                <label class="form-label">住所</label>
                <input type="text" name="companies[${companyIndex}][address]" class="form-control">
            </div>
        </div>`;

        container.insertAdjacentHTML('beforeend', html);
        companyIndex++;
    });
</script>
@endsection
