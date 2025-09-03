@php
    $statusCode = 500;
    $message = config('errors.default');

    if (!is_null($exception)) {
        $statusCode = $exception->getStatusCode();
        $rawMessage = $exception->getMessage();

        if ($statusCode === 404) {
            if (str_contains($rawMessage, config('errors.404_model'))) {
                $message = config('errors.404_model');
            } else {
                $message = config('errors.404_route');
            }
        } else {
            $message = config("errors.messages.{$statusCode}") ?? $rawMessage ?? config('errors.default');
        }
    }
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.meta')
</head>

<body class="bg-body-tertiary" style="padding-top: 4.5rem;">
    <div id="app">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="mt-5 ms-3">
                        <div class="alert alert-danger alert-dismissible fade show">
                            <h3>エラーコード&#160;&#8658;&#160;{{ $statusCode }}</h3>
                            <hr>
                            <h5>{{ $message }}</h5>
                            <h5><a href="{{ route('admin.home') }}">トップページはこちら</a></h5>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
