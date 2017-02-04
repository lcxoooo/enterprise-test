<?php namespace App\Listeners;

use App\Models\EnterpriseSmsQuotaLog;
use App\Repositories\EnterpriseSmsQuotaLogRepository;
use App\Repositories\EnterpriseSmsQuotaRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Created by Zhengqian.Zhu
 * Email: zhuzhengqian@vchangyi.com
 * Date: 2016/11/1
 */
class SaveEnterpriseSmsQuota implements ShouldQueue
{
    use InteractsWithQueue;

    protected $enterpriseSmsQuotaLogRepository;

    protected $enterpriseSmsQuotaRepositoryEloquent;

    public function __construct(
        EnterpriseSmsQuotaRepository $enterpriseSmsQuotaRepositoryEloquent,
        EnterpriseSmsQuotaLogRepository $enterpriseSmsQuotaLogRepository
    ) {
        $this->enterpriseSmsQuotaRepositoryEloquent = $enterpriseSmsQuotaRepositoryEloquent;
        $this->enterpriseSmsQuotaLogRepository      = $enterpriseSmsQuotaLogRepository;
    }

    public function handle($event)
    {
        $enterprise = $event->enterprise;
        $num        = env('DEFAULT_EP_SMS_QUOTA', 500);

        //短信配额
        $this->enterpriseSmsQuotaRepositoryEloquent->create([
            'ep_key'          => $enterprise->ep_key,
            'sms_quota'       => $num,
            'total_sms_quota' => $num
        ]);

        //写入日志
        $this->enterpriseSmsQuotaLogRepository->create([
            'ep_key'    => $enterprise->ep_key,
            'type'      => EnterpriseSmsQuotaLog::TYPE_CHARGE,
            'num'       => $num,
            'record_id' => null,
            'comment'   => '企业注册初始化'
        ]);
    }
}