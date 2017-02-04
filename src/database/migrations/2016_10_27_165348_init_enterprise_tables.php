<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class InitEnterpriseTables
 * @author JohnWang <takato@vip.qq.com>
 */
class InitEnterpriseTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 企业信息表
        Schema::create('enterprises', function (Blueprint $table) {
            $table->string('ep_key')->comment('企业唯一标识符')->unique();
            $table->string('email')->nullable()->comment('企业邮箱');
            $table->string('mobile')->nullable()->comment('企业管理员手机号');
            $table->string('realname')->nullable()->comment('企业管理员真实姓名');
            $table->string('company_name')->nullable()->comment('公司名称');
            $table->string('logo_url')->nullable()->comment('企业 LOGO 地址');
            $table->string('status')->default('NORMAL')->comment('状态')->index();
            $table->timestamps();
        });

        // 企业短信配额表
        Schema::create('enterprise_sms_quotas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ep_key')->comment('企业唯一标识符')->index();
            $table->integer('sms_quota')->default(0)->comment('短信配额')->index();
            $table->integer('used_sms_quota')->default(0)->comment('已使用短信配额')->index();
            $table->integer('total_sms_quota')->default(0)->comment('总计短信配额')->index();
            $table->timestamps();
        });

        // 企业短信配额使用记录表
        Schema::create('enterprise_sms_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ep_key')->comment('企业唯一标识符')->index();
            $table->string('mobile')->comment('手机号码')->index();
            $table->text('content')->comment('短信内容');
            $table->string('send_id')->comment('短信发送 ID');
            $table->integer('num')->comment('短信条数');
            $table->timestamps();
        });

        // 企业全局设置表
        Schema::create('enterprise_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ep_key')->comment('企业唯一标识符')->index();
            $table->string('key')->index();
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 企业号配置表
        Schema::create('wechat_corps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ep_key')->comment('企业唯一标识符')->index();
            $table->string('corp_id')->index()->comment('企业ID');
            $table->string('corp_secret')->nullable()->comment('管理组凭证');
            $table->string('permanent_code')->nullable()->comment('永久授权码');
            $table->text('auth_info')->nullable()->comment('授权完成返回的JSON串');
            $table->string('auth_fail_reason')->nullable()->comment('授权失败的原因');
            $table->string('corp_name')->nullable()->comment('企业名');
            $table->string('corp_round_logo_url')->nullable()->comment('授权方企业号圆形头像');
            $table->string('corp_square_logo_url')->nullable()->comment('授权方企业号方形头像');
            $table->string('qrcode_url')->nullable()->comment('授权方企业号二维码');
            $table->string('chat_secret')->nullable()->comment('企业聊天secret');
            $table->string('chat_token')->nullable()->comment('企业聊天token');
            $table->string('chat_encoding_aes_key')->nullable()->comment('企业聊天encoding');
            $table->string('authorize_type')->default('SUITE')->comment('授权类型,CORP:企业手工授权,SUITE:第三方套件授权')->index();
            $table->string('status')->default('NORMAL')->index()->comment('状态');
            $table->timestamps();
        });

        // 企业号授权应用
        Schema::create('wechat_corp_apps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ep_key')->comment('企业唯一标识符')->index();
            $table->integer('app_id')->comment('套件 APP ID')->index();
            $table->string('type')->comment('套件 APP 类型')->index();
            $table->string('agent_id')->comment('授权方应用id');
            $table->string('token')->nullable()->comment('token,仅在secret授权中使用');
            $table->string('encoding_aes_key')->nullable()->comment('EncodingAESKey，仅在secret授权中使用');
            $table->string('description')->nullable()->comment('描述');
            $table->string('status')->default('NORMAL')->index()->comment('状态');
            $table->timestamps();
        });

        // 企业服务号配置表
        Schema::create('wechat_mps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ep_key')->comment('企业唯一标识符')->index();
            $table->string('app_id')->nullable()->comment('app_id')->index();
            $table->string('app_secret')->nullable()->comment('app_secret');
            $table->string('server_token')->nullable()->comment('服务器令牌');
            $table->string('encoding_aes_key')->nullable()->comment('消息加解密密钥');
            $table->string('component_refresh_token')->nullable()->comment('公众号开放平台刷新token');
            $table->string('authorize_type')->default('COMPONENT')->comment('授权类型,MANUAL 是手工授权，COMPONENT 是第三方组件授权');
            $table->string('nick_name')->nullable()->comment('服务号名称');
            $table->string('head_img')->nullable()->comment('服务号 Logo');
            $table->string('original_id')->nullable()->comment('授权方公众号的原始ID');
            $table->string('alias')->nullable()->comment('授权方公众号所设置的微信号，可能为空');
            $table->string('qrcode_url')->nullable()->comment('二维码图片的URL');
            $table->string('status')->default('NORMAL')->comment('状态')->index();
            $table->boolean('is_edit_menu_item')->default(false)->comment('是否设置过服务号菜单')->index();
            $table->timestamps();
        });

        // 企业服务号模板表
        Schema::create('wechat_mp_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ep_key')->comment('企业唯一标识符')->index();
            $table->string('type')->comment('模板类型')->index();
            $table->string('tpl_id')->comment('模板 ID');
            $table->timestamps();
            $table->softDeletes();
        });

        // 企业服务号菜单事件表
        Schema::create('wechat_mp_menu_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ep_key')->comment('企业唯一标识符')->index();
            $table->string('event')->index()->comment('事件类型，CLICK 点击，VIEW 查看链接，其他见微信公众号文档')->index();
            $table->string('event_key')->index()->comment('事件KEY值，与自定义菜单接口中KEY值对应')->index();
            $table->string('response')->comment('事件回复');
            $table->string('response_type')->default("TEXT")->comment('response 消息类型,TEXT 文本类型，NEWS 图文类型');
            $table->timestamps();
            $table->softDeletes();
        });

        // 第三方授权 ticket
        Schema::create('wechat_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->comment('开放平台类型，MP 服务号，CORP 企业号')->index();
            $table->string('ticket');
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
        Schema::drop('enterprises');
        Schema::drop('enterprise_sms_quotas');
        Schema::drop('enterprise_sms_logs');
        Schema::drop('enterprise_settings');
        Schema::drop('wechat_corps');
        Schema::drop('wechat_corp_apps');
        Schema::drop('wechat_mps');
        Schema::drop('wechat_mp_templates');
        Schema::drop('wechat_mp_menu_events');
        Schema::drop('wechat_tickets');
    }
}
