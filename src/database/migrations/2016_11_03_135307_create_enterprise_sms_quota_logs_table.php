<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateEnterpriseSmsQuotaLogsTable
 * @author JohnWang <takato@vip.qq.com>
 */
class CreateEnterpriseSmsQuotaLogsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // 企业短信配额变动日志
        Schema::create('enterprise_sms_quota_logs', function(Blueprint $table) {
            $table->increments('id');
            $table->string('ep_key')->comment('企业唯一标识符')->index();
            $table->string('type')->comment('类型：充值 CHARGE，人工扣减 DECREASE，消费 CONSUME')->index();
            $table->integer('num')->comment('涉及短信条数');
            $table->string('record_id')->nullable()->comment('短信记录 ID');
            $table->text('comment')->default('')->comment('备注');
            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('enterprise_sms_quota_logs');
	}

}
