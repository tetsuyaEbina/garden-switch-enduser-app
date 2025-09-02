<?php

namespace App\Console\Commands\Batch;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CreateAdminUser extends Command
{
    protected $signature   = 'command:create-admin';
    protected $description = '管理ユーザーを作成(氏名・メールアドレスを指定)';

    public function handle()
    {
        $name  = $this->ask('氏名を入力してください');
        $email = $this->ask('メールアドレスを入力してください');

        $password = env('INITIAL_ADMIN_PASSWORD');

        if (empty($password)) {
            $this->error('.env に INITIAL_ADMIN_PASSWORD が設定されていません。');
            Log::alert('INITIAL_ADMIN_PASSWORD が未設定のため、ユーザー作成処理を中断しました。');
            return 1;
        }

        if (Admin::where('email', $email)->exists()) {
            $this->warn('このメールアドレスはすでに登録されています');
            Log::alert("ユーザー作成スキップ: 既存メールアドレス => {$email}");
            return 0;
        }

        $user = Admin::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
            'is_root'  => 1,
        ]);

        $message = "管理ユーザー作成: {$user->name} ({$user->email})";
        $this->info($message);
        Log::info($message);
        return 0;
    }
}
