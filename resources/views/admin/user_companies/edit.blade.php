@extends('admin.app')
@section('title', '法人編集')

@section('content')
<div class="container">    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>法人編集</h2>
    </div>
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

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.user_companies.update', $company->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">法人ID&#40;変更不可&#41;</label>
            <div class="form-control-plaintext" style="margin-left: 10px">
                {{ $company->user_company_id }}
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">法人名&nbsp;<span class="text-danger">&#42;</label>
            <input type="text" name="user_company_name" class="form-control" value="{{ old('user_company_name', $company->user_company_name) }}" required maxlength="256">
        </div>

        <div class="mb-3">
            <label class="form-label">法人番号&#40;13桁&#41;</label>
            <input type="text" name="corporate_number" class="form-control" value="{{ old('corporate_number', $company->corporate_number) }}" maxlength="13">
        </div>

        <div class="mb-3">
            <label class="form-label">インボイス番号&#40;T&plus;数字13桁&#41;</label>
            <input type="text" name="invoice_number" class="form-control" value="{{ old('invoice_number', $company->invoice_number) }}" maxlength="20">
        </div>

        <div class="mb-3">
            <label class="form-label">住所</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $company->address) }}" maxlength="512">
        </div>

        <button type="submit" class="btn btn-success btn-sm">更新</button>
        <a href="{{ route('admin.user_companies.index') }}" class="btn btn-sm btn-secondary">戻る</a>
    </form>
</div>
@endsection
