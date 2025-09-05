@extends('user.app')
@section('title', 'トップページ')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12">
      @if(optional(Auth::guard('user')->user())->has_custom_flow == 1)
        <h4 class="page-title">店舗全体</h4>
        <hr class="page-divider">
        @include('user.form.search_form')
      @endif
    </div>
  </div>
</div>
@endsection
