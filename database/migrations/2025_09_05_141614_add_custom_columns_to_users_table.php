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
        Schema::table('users', function (Blueprint $table) {
            $table->json('viewable_hall_id_list')->nullable()->after('user_company_id');
            $table->string('department_name', 128)->nullable()->after('viewable_hall_id_list');
            $table->string('position_name', 64)->nullable()->after('department_name');
            $table->string('personal_invoice_number', 20)->nullable()->after('position_name');
            $table->string('personal_address', 512)->nullable()->after('personal_invoice_number');
            $table->json('original_p_ball_unit_price_list')->nullable()->after('personal_address');
            $table->json('original_s_coin_unit_price_list')->nullable()->after('original_p_ball_unit_price_list');
            $table->json('original_machine_type_list')->nullable()->after('original_s_coin_unit_price_list');
            $table->boolean('has_custom_flow')->default(0)->after('original_machine_type_list');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'viewable_hall_id_list',
                'department_name',
                'position_name',
                'personal_invoice_number',
                'personal_address',
                'original_p_ball_unit_price_list',
                'original_s_coin_unit_price_list',
                'original_machine_type_list',
                'has_custom_flow',
            ]);
        });
    }
};
