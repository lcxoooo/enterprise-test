<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Dingo\Api\Event\ResponseWasMorphed' => [
            'App\Listeners\AddCustomResponse'
        ],
        //企业注册
        'App\Events\EnterpriseRegister' => [
            'App\Listeners\SaveEnterpriseSmsQuota',
            'App\Listeners\PublishEnterpriseEvent',
        ],
        // 企业启用
        'App\Events\EnterpriseWasEnable' => [
            'App\Listeners\PublishEnterpriseEvent',
        ],
        // 企业禁用
        'App\Events\EnterpriseWasDisable' => [
            'App\Listeners\PublishEnterpriseEvent',
        ],
        // 企业修改
        'App\Events\EnterpriseWasModified' => [
            'App\Listeners\PublishEnterpriseEvent',
        ],
        //充值成功事件
        'App\Events\EnterpriseSmsQuotaWasCharged' => [
            'App\Listeners\CreateEnterpriseSmsQuotaLog'
        ],
        //扣减配额事件
        'App\Events\EnterpriseSmsQuotaWasDecreased' => [
            'App\Listeners\CreateEnterpriseSmsQuotaLog'
        ],
        //发送短信事件
        'App\Events\EnterpriseSmsWasSent' => [
            'App\Listeners\CreateEnterpriseSmsQuotaLog'
        ],
    ];
}
