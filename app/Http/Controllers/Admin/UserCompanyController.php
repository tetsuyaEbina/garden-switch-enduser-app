<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserCompany;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;

class UserCompanyController extends Controller
{
    /**
     * 法人一覧画面を表示
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 通常表示は件数多い順,ページネーション
        // 絞込み検索がある場合、ページネーションは適用しない
        $search      = $request->input('keyword');
        $onlyTrashed = $request->input('trashed') === '1';
        $query       = UserCompany::withCount([
            'users as users_count' => function (Builder $query) {
                $query->withTrashed();
            }
        ]);

        if ($onlyTrashed) {
            $query->onlyTrashed();
        }

        // 名称検索あり->全件取得(非ページネーション)
        if (!empty($search)) {
            $userCompanies = $query->where('user_company_name', 'like', '%' . $search . '%')
                ->orderByDesc('users_count')
                ->get();
            $paginate = false;
        } else {
            // 通常表示
            $userCompanies = $query->orderByDesc('users_count')
                ->paginate(config('pagination.halls', config('pagination.default', 20)))
                ->withQueryString();
            $paginate = true;
        }

        return view('admin.user_companies.index', compact('userCompanies', 'search', 'onlyTrashed', 'paginate'));
    }

    /**
     * 法人作成画面を表示
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.user_companies.create');
    }

    /**
     * 複数の法人を登録する（最大5件）
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->input('companies', []);
        if (count($data) > 5){
            return redirect()->route('admin.user_companies.create')->with('error', '登録可能最大件数は5件です。');
        }

        $rules = [
            'user_company_id'   => 'required|integer|unique:user_companies,user_company_id',
            'user_company_name' => 'required|string|max:256',
            'corporate_number'  => 'nullable|string|max:13|unique:user_companies,corporate_number',
            'invoice_number'    => 'nullable|string|max:20|unique:user_companies,invoice_number',
            'address'           => 'nullable|string|max:512',
        ];

        $messages = [
            'user_company_id.required'   => '法人IDは必須です。',
            'user_company_id.integer'    => '法人IDは数値で入力してください。',
            'user_company_id.unique'     => '既に使用されている法人IDです。',
            'user_company_name.required' => '法人名は必須です。',
            'user_company_name.max'      => '法人名は256文字以内で入力してください。',
            'corporate_number.max'       => '法人番号は13文字以内で入力してください。',
            'corporate_number.unique'    => '既に使用されている法人番号です。',
            'invoice_number.max'         => 'インボイス番号は20文字以内で入力してください。',
            'invoice_number.unique'      => '既に使用されているインボイス番号です。',
            'address.max'                => '住所は512文字以内で入力してください。',
        ];

        // 同一入力内での重複チェック用配列
        $seenIds             = [];
        $seenCorporateNumber = [];
        $seenInvoiceNumber   = [];
        foreach ($data as $index => $entry) {
            // 全部空ならスキップ
            if (empty(array_filter($entry))) {
                unset($data[$index]);
                continue;
            }

            // 同一入力内の重複チェック
            if (in_array($entry['user_company_id'], $seenIds)) {
                return back()->withErrors(["companies.$index.user_company_id" => "同じ法人IDが複数入力されています。"])->withInput();
            }
            if (in_array($entry['corporate_number'], $seenCorporateNumber)) {
                return back()->withErrors(["companies.$index.corporate_number" => "同じ法人番号が複数入力されています。"])->withInput();
            }
            if (in_array($entry['invoice_number'], $seenInvoiceNumber)) {
                return back()->withErrors(["companies.$index.invoice_number" => "同じインボイス番号が複数入力されています。"])->withInput();
            }
            $seenIds[]             = $entry['user_company_id'];
            $seenCorporateNumber[] = $entry['corporate_number'];
            $seenInvoiceNumber[]   = $entry['invoice_number'];

            $validator = Validator::make($entry, $rules, $messages);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
        }

        // 登録処理
        DB::transaction(function () use ($data) {
            foreach ($data as $entry) {
                UserCompany::create($entry);
            }
        });

        return redirect()->route('admin.user_companies.index')->with('success', '法人を登録しました。');
    }

    /**
     * 法人情報編集画面を表示
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $company = UserCompany::findOrFail($id);
        return view('admin.user_companies.edit', compact('company'));
    }

    /**
     * 法人情報を更新
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $company = UserCompany::findOrFail($id);

        $request->validate([
            'user_company_name' => "required|string|max:256|",
            'corporate_number'  => "nullable|string|max:13|unique:user_companies,corporate_number,{$id}",
            'invoice_number'    => "nullable|string|max:20|unique:user_companies,invoice_number,{$id}",
            'address'           => 'nullable|string|max:512',
        ], [
            'user_company_name.required' => '法人名は必須です。',
            'user_company_name.string'   => '法人名は文字列で入力してください。',
            'user_company_name.max'      => '法人名は256文字以内で入力してください。',
            'corporate_number.max'       => '法人番号は13文字以内で入力してください。',
            'corporate_number.unique'    => '既に使用されている法人番号です。',
            'invoice_number.max'         => 'インボイス番号は20文字以内で入力してください。',
            'invoice_number.unique'      => '既に使用されているインボイス番号です。',
            'address.max'                => '住所は512文字以内で入力してください。',
        ]);

        DB::transaction(function () use ($company, $request) {
            $company->update($request->all());
        });

        return redirect()->route('admin.user_companies.index')->with('success', '法人情報を更新しました。');
    }

    /**
     * 法人を削除(論理削除)
     * ユーザが紐づいている場合は該当ユーザも論理削除
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $company = UserCompany::findOrFail($id);
        DB::transaction(function () use ($company) {
            // 紐づくユーザと法人を論理削除
            User::where('user_company_id', $company->user_company_id)->delete();
            $company->delete();
        });

        return redirect()->route('admin.user_companies.index')
            ->with('success', "法人（{$company->user_company_name}）および所属ユーザを削除しました。");
    }

    /**
     * 論理削除された法人を復元
     * ユーザが紐づいている場合は該当ユーザも復元
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $company = UserCompany::onlyTrashed()->findOrFail($id);
        DB::transaction(function () use ($company) {
            // 法人と所属ユーザ復元
            $company->restore();
            User::onlyTrashed()
                ->where('user_company_id', $company->user_company_id)
                ->restore();
        });

        return redirect()->route('admin.user_companies.index')->with('success', "法人（{$company->user_company_name}）と所属ユーザを復元しました。");
    }
}
