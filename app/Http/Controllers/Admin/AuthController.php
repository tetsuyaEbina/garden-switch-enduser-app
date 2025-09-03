<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * 管理者ログインフォームを表示する
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * 管理者のログイン処理を行う
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            // 他セッションを削除（多重ログイン防止）
            $admin            = Auth::guard('admin')->user();
            $currentSessionId = Session::getId();
            $sessions         = DB::table('sessions')->where('user_id', $admin->id)->get();
            foreach ($sessions as $session) {
                try {
                    $data = unserialize(base64_decode($session->payload));
                    foreach ($data as $key => $value) {
                        // login_admin_ というキー名を持つかを確認
                        if (str_starts_with($key, 'login_admin_')) {
                            if ($session->id !== $currentSessionId) {
                                DB::table('sessions')->where('id', $session->id)->delete();
                            }
                            break;
                        }
                    }
                } catch (\Throwable $e) {
                    // エラーは無視
                    continue;
                }
            }
            return redirect()->intended(route('admin.home'));
        }

        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが違います。',
        ]);
    }

    /**
     * 管理者のログアウト処理を行う
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
