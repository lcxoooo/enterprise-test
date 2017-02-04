<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEnterpriseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enterprises', function (Blueprint $table) {
            $table->boolean('store_on')->default(false)->after('logo_url');
            $table->boolean('cashier_on')->default(false)->after('store_on');
            $table->boolean('seller_mode_on')->default(false)->after('cashier_on');
            $table->string('store_type')->after('seller_mode_on')->default('PROBATION')->comment('微店类型，试用: PROBATION 付费:PAY 永久：PERMANENT');
            $table->timestamp('store_expires_at')->after('store_type')->nullable()->comment('微店过期时间');
            $table->string('cashier_type')->after('store_expires_at')->default('PROBATION')->comment('收银台类型，试用: PROBATION 付费:PAY 永久：PERMANENT');
            $table->timestamp('cashier_expires_at')->after('cashier_type')->nullable()->comment('收银台过期时间');
            $table->integer('owned_max_store')->default(10)->after('cashier_expires_at')->comment('最大门店数');
            $table->integer('owned_max_guide')->default(30)->after('owned_max_store')->comment('最大导购数');
            $table->string('level')->default('UNPROCESSED')->after('owned_max_guide')->comment('企业登记');
            $table->boolean('erp_on')->default(false)->after('level')->comment('是否开启erp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enterprises', function (Blueprint $table) {
            $table->dropColumn('store_on');
            $table->dropColumn('cashier_on');
            $table->dropColumn('seller_mode_on');
            $table->dropColumn('store_type');
            $table->dropColumn('store_expires_at');
            $table->dropColumn('cashier_type');
            $table->dropColumn('cashier_expires_at');
            $table->dropColumn('owned_max_store');
            $table->dropColumn('owned_max_guide');
            $table->dropColumn('level');
            $table->dropColumn('erp_on');
        });
    }
}
