<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    /**
     * 管理者一覧を取得し表示する
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $admins = Admin::orderBy('created_at', 'desc')->get();
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * 管理者新規作成フォームを表示する
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $initialPassword = config('admin.initial_password');
        return view('admin.admins.create', compact('initialPassword'));
    }

    /**
     * 管理者を登録する
     *
     * 初期パスワードは config('admin.initial_password') を使用し、Hash 化して保存。
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
        ], [
            'name.required'  => '氏名は必須です。',
            'name.max'       => '氏名は255文字以内で入力してください。',
            'email.required' => 'メールアドレスは必須です。',
            'email.email'    => '有効なメールアドレス形式で入力してください。',
            'email.unique'   => 'このメールアドレスは既に登録されています。',
        ]);

        $initialPassword = config('admin.initial_password');
        DB::transaction(function () use ($request, $initialPassword) {
            Admin::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($initialPassword),
                'is_root'  => 0,
            ]);
        });

        return redirect()->route('admin.admins.index')->with('success', '管理者を登録しました');
    }

    /**
     * 管理者編集フォームを表示する
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * 管理者情報を更新する
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id,
        ], [
            'name.required'  => '氏名は必須です。',
            'name.max'       => '氏名は255文字以内で入力してください。',
            'email.required' => 'メールアドレスは必須です。',
            'email.email'    => '有効なメールアドレス形式で入力してください。',
            'email.unique'   => 'このメールアドレスは既に登録されています。',
        ]);

        DB::transaction(function () use ($request, $admin) {
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->save();
        });

        return redirect()->route('admin.admins.index')->with('success', "id --> {$admin->id} の管理者情報を更新しました");
    }

    /**
     * 管理者を削除する（物理削除）
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $admin = Admin::findOrFail($id);
        if ($admin->is_root == 1) {
            return redirect()->route('admin.admins.index')->with('error', 'ルート管理者は削除できません');
        }
        $admin->delete();

        // loginしている場合、強制logoutさせる
        $sessions = DB::table('sessions')
            ->where('user_id', $admin->id)
            ->get();

        foreach ($sessions as $session) {
            try {
                $data = unserialize(base64_decode($session->payload));
                foreach ($data as $key => $value) {
                    // login_admin_ というキー名を持つかを確認
                    if (str_starts_with($key, 'login_admin_')) {
                        DB::table('sessions')->where('id', $session->id)->delete();
                        break;
                    }
                }
            } catch (\Throwable $e) {
                // エラーは無視
                continue;
            }
        }
        return redirect()->route('admin.admins.index')->with('success', "id --> {$id} のユーザ管理者を削除しました");
    }

    /**
     * 指定した管理者のパスワードを初期化する
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword($id)
    {
        $admin = Admin::findOrFail($id);
        $currentAdmin = Auth::guard('admin')->user();

        // root以外はPWの初期化禁止
        if ($currentAdmin->is_root !== 1) {
            return redirect()->route('admin.admins.index')->with('error', 'rootユーザ以外は、パスワードを初期化できません');
        }

        $initialPassword = config('admin.initial_password');
        DB::transaction(function () use ($admin, $initialPassword) {
            $admin->password = Hash::make($initialPassword);
            $admin->save();
        });

        // パスワードをリセットした場合は、該当ユーザを強制ログアウト
        $sessions = DB::table('sessions')
            ->where('user_id', $admin->id)
            ->get();
        foreach ($sessions as $session) {
            try {
                $data = unserialize(base64_decode($session->payload));
                foreach ($data as $key => $value) {
                    // login_admin_ というキー名を持つかを確認
                    if (str_starts_with($key, 'login_admin_')) {
                        DB::table('sessions')->where('id', $session->id)->delete();
                        break;
                    }
                }
            } catch (\Throwable $e) {
                // エラーは無視
                continue;
            }
        }
        if ($admin->id === $currentAdmin->id) {
            return redirect()->route('admin.login')->with('success', 'パスワードを初期化しました。再ログインしてください。');
        }else {
            return redirect()->route('admin.admins.index')->with('success', "id --> {$admin->id} のユーザのパスワードを初期化しました");
        }
    }

    /**
     * パスワード変更画面を表示する
     *
     * @return \Illuminate\View\View
     */
    public function editPassword()
    {
        return view('admin.admins.password');
    }

    /**
     * パスワード変更処理
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'new_password'          => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => '現在のパスワードを入力してください。',
            'new_password.required'     => '新しいパスワードを入力してください。',
            'new_password.min'          => '新しいパスワードは8文字以上で入力してください。',
            'new_password.confirmed'    => '新しいパスワードが一致しません。',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => '現在のパスワードが間違っています。']);
        }

        $admin = Admin::findOrFail($admin->id);
        $admin->password = Hash::make($request->new_password);
        $admin->save();

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'パスワードを変更しました。再度ログインしてください。');
    }
}
