<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_company_id')->unique();
            $table->string('user_company_name', 256);
            $table->string('corporate_number', 13)->nullable()->unique();
            $table->string('invoice_number', 20)->nullable()->unique();
            $table->string('address', 512)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // users テーブルに user_company_id カラム追加(外部キー付き)
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('user_company_id')->nullable()->after('remember_token');

            $table->foreign('user_company_id')
                ->references('user_company_id')
                ->on('user_companies')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 外部キー制約削除
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['user_company_id']);
            $table->dropColumn('user_company_id');
        });
        Schema::dropIfExists('user_companies');
    }
};
