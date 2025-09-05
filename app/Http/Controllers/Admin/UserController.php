<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\MasterData\Hall;

class UserController extends Controller
{
    /**
     * ユーザ一覧画面を表示
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search      = $request->input('keyword');
        $onlyTrashed = $request->input('trashed') === '1';
        $query       = User::with('userCompany');

        if ($onlyTrashed) {
            $query->onlyTrashed();
        }

        // 名称検索あり->全件取得(非ページネーション)
        if (!empty($search)) {
            $paginate = false;
            $users    = $query->where('name', 'like', '%' . $search . '%')->orderBy('name')->get();
        } else {
            // 通常表示
            $paginate = true;
            $users    = $query->orderBy('created_at', 'desc')
                ->paginate(config('pagination.users', config('pagination.default', 20)))
                ->withQueryString();
        }

        return view('admin.users.index', compact('users', 'search', 'onlyTrashed', 'paginate'));
    }

    /**
     * ユーザ新規作成画面を表示
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $initialPassword = config('user.initial_password');
        $userCompanies   = UserCompany::orderBy('user_company_name')->get();
        $halls           = Hall::orderBy('hall_name')->get();

        return view('admin.users.create', compact('initialPassword', 'userCompanies', 'halls'));
    }

    /**
     * ユーザ登録処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                    => ['required', 'string', 'max:255'],
            'email'                   => ['required', 'email', 'max:255', 'unique:users,email'],
            'user_company_id'         => ['nullable', 'exists:user_companies,user_company_id'],
            'viewable_hall_id_list'   => ['nullable', 'array'],
            'viewable_hall_id_list.*' => [
                Rule::exists((new Hall)->getConnectionName() . '.' . (new Hall)->getTable(), 'hall_id'),
            ],
            'department_name'         => ['nullable', 'string', 'max:128'],
            'position_name'           => ['nullable', 'string', 'max:64'],
            'personal_invoice_number' => ['nullable', 'string', 'max:20'],
            'personal_address'        => ['nullable', 'string', 'max:512'],
            'has_custom_flow'         => ['required', 'in:0,1'],
        ], [
            'name.required'                  => '氏名は必須です。',
            'name.max'                       => '氏名は255文字以内で入力してください。',
            'email.required'                 => 'メールアドレスは必須です。',
            'email.email'                    => '正しいメールアドレスの形式で入力してください。',
            'email.max'                      => 'メールアドレスは255文字以内で入力してください。',
            'email.unique'                   => 'このメールアドレスは既に登録されています。',
            'user_company_id.exists'         => '選択された法人が存在しません。',
            'viewable_hall_id_list.array'    => '閲覧可能ホールの形式が不正です。',
            'viewable_hall_id_list.*.exists' => '指定されたホールが存在しません。',
            'department_name.max'            => '部署名は128文字以内で入力してください。',
            'position_name.max'              => '役職名は64文字以内で入力してください。',
            'personal_invoice_number.max'    => 'インボイス番号は20文字以内で入力してください。',
            'personal_address.max'           => '請求先住所は512文字以内で入力してください。',
            'has_custom_flow.required'       => 'カスタマイズUIの選択は必須です。',
            'has_custom_flow.in'             => 'カスタマイズUIの選択が不正です。',
        ]);

        try {
            DB::beginTransaction();

            $initialPassword               = config('user.initial_password');
            $user                          = new User();
            $user->name                    = $request->input('name');
            $user->email                   = $request->input('email');
            $user->password                = Hash::make($initialPassword);
            $user->user_company_id         = $request->input('user_company_id');
            $user->department_name         = $request->input('user_company_id') ? $request->input('department_name') : null;
            $user->position_name           = $request->input('user_company_id') ? $request->input('position_name') : null;
            $user->personal_invoice_number = $request->input('user_company_id') ? null : $request->input('personal_invoice_number');
            $user->personal_address        = $request->input('user_company_id') ? null : $request->input('personal_address');
            $user->has_custom_flow         = $request->boolean('has_custom_flow') ? 1 : 0;
            $hallIds                       = $request->input('viewable_hall_id_list');
            $user->viewable_hall_id_list   = $hallIds ? json_encode($hallIds, JSON_UNESCAPED_UNICODE) : json_encode([]);
            $user->save();

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'ユーザを登録しました。');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ユーザ登録失敗: ' . $e->getMessage());

            return back()->withInput()->with('error', 'ユーザの登録中にエラーが発生しました。');
        }
    }

    /**
     * ユーザ情報編集画面を表示
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $user                        = User::findOrFail($id);
        $user->viewable_hall_id_list = json_decode($user->viewable_hall_id_list ?? '[]', true);
        $userCompanies               = UserCompany::orderBy('user_company_name')->get();
        $halls                       = Hall::orderBy('hall_name')->get();

        return view('admin.users.edit', compact('user', 'userCompanies', 'halls'));
    }

    /**
     * ユーザ情報更新処理
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name'                    => ['required', 'string', 'max:255'],
            'email'                   => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'user_company_id'         => ['nullable', 'exists:user_companies,user_company_id'],
            'viewable_hall_id_list'   => ['nullable', 'array'],
            'viewable_hall_id_list.*' => [
                Rule::exists((new Hall)->getConnectionName() . '.' . (new Hall)->getTable(), 'hall_id'),
            ],
            'department_name'         => ['nullable', 'string', 'max:128'],
            'position_name'           => ['nullable', 'string', 'max:64'],
            'personal_invoice_number' => ['nullable', 'string', 'max:20'],
            'personal_address'        => ['nullable', 'string', 'max:512'],
            'has_custom_flow'         => ['required', 'in:0,1'],
        ], [
            'name.required'                  => '氏名は必須です。',
            'email.required'                 => 'メールアドレスは必須です。',
            'email.email'                    => '有効なメールアドレス形式で入力してください。',
            'email.unique'                   => 'このメールアドレスは既に登録されています。',
            'user_company_id.exists'         => '選択された法人が存在しません。',
            'viewable_hall_id_list.array'    => '閲覧可能ホールの形式が不正です。',
            'viewable_hall_id_list.*.exists' => '指定されたホールが存在しません。',
            'department_name.max'            => '部署名は128文字以内で入力してください。',
            'position_name.max'              => '役職名は64文字以内で入力してください。',
            'personal_invoice_number.max'    => 'インボイス番号は20文字以内で入力してください。',
            'personal_address.max'           => '請求先住所は512文字以内で入力してください。',
            'has_custom_flow.in'             => 'カスタマイズUIは「はい」か「いいえ」を選択してください。',
        ]);

        try {
            DB::beginTransaction();

            $user->name                    = $request->input('name');
            $user->email                   = $request->input('email');
            $user->user_company_id         = $request->input('user_company_id');
            $user->department_name         = $request->input('user_company_id') ? $request->input('department_name') : null;
            $user->position_name           = $request->input('user_company_id') ? $request->input('position_name') : null;
            $user->personal_invoice_number = $request->input('user_company_id') ? null : $request->input('personal_invoice_number');
            $user->personal_address        = $request->input('user_company_id') ? null : $request->input('personal_address');
            $user->has_custom_flow         = $request->boolean('has_custom_flow') ? 1 : 0;
            $hallIds                       = $request->input('viewable_hall_id_list');
            $user->viewable_hall_id_list   = $hallIds ? json_encode($hallIds, JSON_UNESCAPED_UNICODE) : json_encode([]);
            $user->save();

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', "ユーザ {{$user->name}}(ID: {{$user->id}}) の情報を更新しました。");

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('ユーザ更新失敗: ' . $e->getMessage());

            return back()->withInput()->with('error', 'ユーザ情報の更新中にエラーが発生しました。');
        }
    }

    /**
     * ユーザーを論理削除(無効化)
     * 論理削除されたユーザは強制ログアウト
     *
     * @param int $id ユーザーID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            // loginしている場合、強制logoutさせる
            $sessions = DB::table('sessions')
                ->where('user_id', $user->id)
                ->get();
            foreach ($sessions as $session) {
                try {
                    $data = unserialize(base64_decode($session->payload));
                    foreach ($data as $key => $value) {
                        // login_user_ というキー名を持つかを確認
                        if (str_starts_with($key, 'login_user_')) {
                            DB::table('sessions')->where('id', $session->id)->delete();
                            break;
                        }
                    }
                } catch (\Throwable $e) {
                    // エラーは無視
                    continue;
                }
            }

            return back()->with('success', "ユーザー {$user->name}(ID: {$user->id}) を無効にしました。");
        } catch (\Throwable $e) {
            Log::error('ユーザー削除失敗: ' . $e->getMessage());
            return back()->with('error', 'ユーザーの無効化に失敗しました。');
        }
    }

    /**
     * 論理削除されたユーザーを復元(有効化)
     *
     * @param int $id ユーザーID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(int $id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();

            return back()->with('success', "ユーザー {$user->name}(ID: {$user->id}) を有効にしました。");
        } catch (\Throwable $e) {
            Log::error('ユーザー復元失敗: ' . $e->getMessage());
            return back()->with('error', 'ユーザーの復元に失敗しました。');
        }
    }

    /**
     * ユーザのパスワードを初期化
     * パスワードは .env に定義された `USER_INITIAL_PASSWORD` を使用
     * パスワードを初期化されたユーザは強制ログアウト
     *
     * @param int $id ユーザーID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(int $id)
    {
        try {
            $user            = User::withTrashed()->findOrFail($id);
            $initialPassword = config('user.initial_password');
            $user->password  = Hash::make($initialPassword);
            $user->save();

            // loginしている場合、強制logoutさせる
            $sessions = DB::table('sessions')
                ->where('user_id', $user->id)
                ->get();
            foreach ($sessions as $session) {
                try {
                    $data = unserialize(base64_decode($session->payload));
                    foreach ($data as $key => $value) {
                        // login_user_ というキー名を持つかを確認
                        if (str_starts_with($key, 'login_user_')) {
                            DB::table('sessions')->where('id', $session->id)->delete();
                            break;
                        }
                    }
                } catch (\Throwable $e) {
                    // エラーは無視
                    continue;
                }
            }

            return back()->with('success', "ユーザ {$user->name}(ID: {$user->id}) のパスワードを初期化しました。");
        } catch (\Throwable $e) {
            Log::error('パスワード初期化失敗: ' . $e->getMessage());
            return back()->with('error', 'パスワードの初期化に失敗しました。');
        }
    }
}
