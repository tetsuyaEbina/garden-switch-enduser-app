<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, $exception)
    {
        // サブディレクトリによってテンプレート分岐
        // log-viewerに未認証の状態でアクセスされた場合は、強制リダイレクト
        if ($request->is('log-viewer*')) {
            return redirect('/');
        } elseif ($request->is('user*')) {
            $prefix = 'user.errors';
        } elseif ($request->is('admin*')) {
            $prefix = 'admin.errors';
        } else {
            $prefix = 'errors';
        }

        // ModelNotFoundException を 404 に変換（メッセージは config から）
        if ($exception instanceof ModelNotFoundException) {
            $exception = new NotFoundHttpException(config('errors.404_model'), $exception);
        }

        // HTTP エラーの場合
        if ($this->isHttpException($exception)) {
            $httpException = $exception instanceof HttpException
                ? $exception
                : new HttpException(500, $exception->getMessage());

            return response()->view(
                "{$prefix}.common",
                ['exception' => $exception],
                $httpException->getStatusCode()
            );
        }

        // それ以外（Throwable等）は通常通り
        return parent::render($request, $exception);
    }
}
