<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

/** @var $api \Dingo\Api\Routing\Router */
$api = app(Dingo\Api\Routing\Router::class);

// v1 version API
// choose version add this in header    Accept:application/vnd.lumen.v1+json
$api->version('v1', [
    'namespace'  => 'App\Http\Controllers\Api\V1',
    'middleware' => ['cors']
], function ($api) {
    /** @var $api \Dingo\Api\Routing\Router */

    //SDK debug API
    $api->group(['prefix'=>'sdk'],function($api){
        $api->get('debug','SdkDebugController@debug');
    });


    // 企业
    $api->group(['prefix' => 'enterprises'], function ($api) {
        /** @var $api \Dingo\Api\Routing\Router */

        $api->patch('/{ep_key}/enable', ['as' => 'enterprise.enable', 'uses' => 'EnterpriseController@patchEnable']);
        $api->patch('/{ep_key}/disable', ['as' => 'enterprise.disable', 'uses' => 'EnterpriseController@patchDisable']);
        $api->patch('/{ep_key}', ['as' => 'enterprise.modify', 'uses' => 'EnterpriseController@patchModify']);
        $api->get('check_mobile_exist/{mobile}',['as'=>'enterprise.checkExist','uses'=>'EnterpriseController@checkEnterpriseExistByMobile']);
        $api->get('/{ep_key}', ['as' => 'enterprise.show', 'uses' => 'EnterpriseController@getShow']);
        $api->post('/register', ['as' => 'enterprise.register', 'uses' => 'EnterpriseController@postRegister']);
        $api->get('/', ['as' => 'enterprise.list', 'uses' => 'EnterpriseController@index']);
    });

    // 企业配置
    $api->group(['prefix' => 'settings'], function ($api) {
        /** @var $api \Dingo\Api\Routing\Router */

        $api->put('/{ep_key}', ['as' => 'settings.modify', 'uses' => 'SettingController@putModify']);
        $api->get('/{ep_key}', ['as' => 'settings.show', 'uses' => 'SettingController@getShow']);
    });

    // 企业短信配额
    $api->group(['prefix' => 'sms'], function ($api) {
        /** @var $api \Dingo\Api\Routing\Router */

        $api->post('/{ep_key}/send', ['as' => 'sms.send', 'uses' => 'SmsController@postSendEnterpriseSms']);
        $api->post('general_send',['as'=>'sms.general_send','uses'=>'SmsController@postGeneralSend']);
        $api->get('/{ep_key}/records', ['as' => 'sms.records', 'uses' => 'SmsController@getRecords']);
        $api->patch('/{ep_key}/charge', ['as' => 'sms.charge', 'uses' => 'SmsController@patchCharge']);
        $api->patch('/{ep_key}/decrease', ['as' => 'sms.decrease', 'uses' => 'SmsController@patchDecrease']);
        $api->get('/{ep_key}', ['as' => 'sms.show', 'uses' => 'SmsController@getShow']);
    });

    // 服务号
    $api->group(['prefix' => 'wechat_mps'], function ($api) {
        /** @var $api \Dingo\Api\Routing\Router */

        $api->get('/ticket', ['as' => 'wechat_mp.get_ticket', 'uses' => 'WechatMpController@getTicket']);
        $api->post('/ticket', ['as' => 'wechat_mp.set_ticket', 'uses' => 'WechatMpController@postTicket']);
        $api->patch('/{ep_key}/enable', ['as' => 'wechat_mp.enable', 'uses' => 'WechatMpController@patchEnable']);
        $api->patch('/{ep_key}/disable', ['as' => 'wechat_mp.disable', 'uses' => 'WechatMpController@patchDisable']);
        $api->get('/{ep_key}', ['as' => 'wechat_mp.show', 'uses' => 'WechatMpController@getShow']);
        $api->post('/{ep_key}', ['as' => 'wechat_mp.create', 'uses' => 'WechatMpController@postCreate']);
        $api->put('/{ep_key}', ['as' => 'wechat_mp.modify', 'uses' => 'WechatMpController@putModify']);
        $api->get('/', ['as' => 'wechat_mp.list', 'uses' => 'WechatMpController@index']);
    });

    // 企业号
    $api->group(['prefix' => 'wechat_corps'], function ($api) {
        /** @var $api \Dingo\Api\Routing\Router */

        $api->get('/ticket', ['as' => 'wechat_corp.get_ticket', 'uses' => 'WechatCorpController@getTicket']);
        $api->post('/ticket', ['as' => 'wechat_corp.set_ticket', 'uses' => 'WechatCorpController@postTicket']);
        $api->patch('/{ep_key}/enable', ['as' => 'wechat_corp.enable', 'uses' => 'WechatCorpController@patchEnable']);
        $api->patch('/{ep_key}/disable', ['as' => 'wechat_corp.disable', 'uses' => 'WechatCorpController@patchDisable']);
        $api->post('/{ep_key}/apps', ['as' => 'wechat_corp.create_app', 'uses' => 'WechatCorpController@postCreateApp']);
        $api->post('/{ep_key}', ['as' => 'wechat_corp.create', 'uses' => 'WechatCorpController@postCreate']);
        $api->put('/{ep_key}', ['as' => 'wechat_corp.modify', 'uses' => 'WechatCorpController@putModify']);
        $api->get('/{ep_key}', ['as' => 'wechat_corp.show', 'uses' => 'WechatCorpController@getShow']);
        $api->get('/', ['as' => 'wechat_corp.list', 'uses' => 'WechatCorpController@index']);
    });
});