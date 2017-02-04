<?php
namespace App\Listeners;

/**
 * Class PublishEnterpriseEvent
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Listeners
 */
class PublishEnterpriseEvent
{

    public function __construct()
    {

    }

    public function handle($event)
    {
        $enterprise = $event->enterprise;

         try {
             app('pubsub')->publish('enterprise', ['type' => $event->type, 'data' => ['ep_key' => $enterprise->ep_key]]);
         } catch (\Exception $e) {
             app('log')->error($e);
         }
    }
}