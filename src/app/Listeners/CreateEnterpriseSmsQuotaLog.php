<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/11/3
 * Time: 16:16
 */

namespace app\Listeners;


use App\Events\EnterpriseSmsQuotaWasCharged;
use App\Events\EnterpriseSmsQuotaWasDecreased;
use App\Events\EnterpriseSmsWasSent;
use App\Events\Event;
use app\Exceptions\EnterpriseSmsQuotaLogSaveFailedException;
use App\Models\EnterpriseSmsQuotaLog;
use App\Repositories\EnterpriseSmsQuotaLogRepository;

/**
 * Class CreateEnterpriseSmsQuotaLog
 * @author  JohnWang <takato@vip.qq.com>
 * @package app\Listeners
 */
class CreateEnterpriseSmsQuotaLog
{

    /**
     * @var EnterpriseSmsQuotaLogRepository
     */
    protected $enterpriseSmsQuotaLogRepository;

    /**
     * CreateEnterpriseSmsQuotaLog constructor.
     * @param EnterpriseSmsQuotaLogRepository $enterpriseSmsQuotaLogRepository
     */
    public function __construct(EnterpriseSmsQuotaLogRepository $enterpriseSmsQuotaLogRepository)
    {
        $this->enterpriseSmsQuotaLogRepository = $enterpriseSmsQuotaLogRepository;
    }

    /**
     * @param Event $event
     * @author         JohnWang <takato@vip.qq.com>
     */
    public function handle(Event $event)
    {
        $enterpriseSmsQuota = $event->enterpriseSmsQuota;

        // 企业短信配额对象不存在
        if (!$enterpriseSmsQuota) {
            return;
        }

        $params = [
            'ep_key' => $enterpriseSmsQuota->ep_key
        ];

        if ($event instanceof EnterpriseSmsQuotaWasCharged) {
            $params['type'] = EnterpriseSmsQuotaLog::TYPE_CHARGE;
            $params['num'] = $event->num;
            $params['comment'] = $event->comment;
        } else if ($event instanceof EnterpriseSmsQuotaWasDecreased) {
            $params['type'] = EnterpriseSmsQuotaLog::TYPE_DECREASE;
            $params['num'] = -$event->num;
            $params['comment'] = $event->comment;
        } else if ($event instanceof EnterpriseSmsWasSent) {
            // 短信发送记录不存在
            if (!$event->enterpriseSmsRecord) {
                return;
            }

            $params['type'] = EnterpriseSmsQuotaLog::TYPE_CONSUME;
            $params['num'] = -$event->enterpriseSmsRecord->num;
            $params['record_id'] = $event->enterpriseSmsRecord->id;
            $params['comment'] = $event->comment;
        } else {
            return;
        }

        // 创建企业短信配额日志
        $enterpriseSmsQuotaLog = $this->enterpriseSmsQuotaLogRepository->create($params);

        // 保存失败
        if (!$enterpriseSmsQuotaLog) {
            throw new EnterpriseSmsQuotaLogSaveFailedException();
        }
    }
}