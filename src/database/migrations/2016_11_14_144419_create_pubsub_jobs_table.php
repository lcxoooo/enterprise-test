<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePubsubJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 订阅发布任务表
        Schema::create('pubsub_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->comment('类型：PUB 发布，SUB 订阅')->index();
            $table->string('channel')->comment('订阅频道')->index();
            $table->integer('offset')->default(0)->comment('订阅位移')->index();
            $table->text('payload')->comment('内容');
            $table->string('status')->default('PROCESSING')->comment('状态：PROCESSING 处理中，SUCCEEDED 处理成功，FAILED 处理失败')->index();
            $table->integer('retry_times')->default(0)->comment('失败重试次数')->index();
            $table->string('failed_reason')->nullable()->comment('失败原因');
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
        //
    }
}
