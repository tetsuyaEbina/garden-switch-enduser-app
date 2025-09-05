<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * ユーザのログインフォームを表示する
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('user.auth.login');
    }

    /**
     * ユーザのログイン処理を行う
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

        if (Auth::guard('user')->attempt($credentials)) {
            $request->session()->regenerate();
            // 他セッションを削除（多重ログイン防止）
            $user             = Auth::guard('user')->user();
            $currentSessionId = Session::getId();
            $sessions         = DB::table('sessions')->where('user_id', $user->id)->get();
            foreach ($sessions as $session) {
                try {
                    $data = unserialize(base64_decode($session->payload));
                    foreach ($data as $key => $value) {
                        // login_user_ というキー名を持つかを確認
                        if (str_starts_with($key, 'login_user_')) {
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
            return redirect()->intended(route('user.home'));
        }

        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが違います。',
        ]);
    }

    /**
     * ユーザのログアウト処理を行う
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('user')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('user.login');
    }

    /**
     * パスワード再設定フォームを表示
     *
     * @return \Illuminate\View\View
     */
    public function showResetPasswordForm()
    {
        return view('user.auth.reset_password');
    }

    /**
     * パスワード再設定処理
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'current_password'          => ['required'],
            'new_password'              => ['required', 'confirmed', 'min:8'],
            'new_password_confirmation' => ['required'],
        ], [
            'current_password.required' => '現在のパスワードを入力してください。',
            'new_password.required'     => '新しいパスワードを入力してください。',
            'new_password.confirmed'    => '新しいパスワードと確認用が一致しません。',
            'new_password.min'          => '新しいパスワードは8文字以上で入力してください。',
        ]);

        $userId = Auth::guard('user')->user()->id;
        $user   = User::findOrFail($userId);

        // 現在のパスワード確認
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => '現在のパスワードが正しくありません。',
            ]);
        }

        // パスワード更新
        $user->password = Hash::make($request->new_password);
        $user->save();

        // セッション再生成
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login')->with('success', 'パスワードを変更しました。再度ログインしてください。');
    }
}
