<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/11/3
 * Time: 14:02
 */

namespace App\Services;


use App\Criteria\EnterpriseCriteria;
use App\Events\EnterpriseSmsQuotaWasCharged;
use App\Events\EnterpriseSmsQuotaWasDecreased;
use App\Events\EnterpriseSmsWasSent;
use App\Exceptions\EnterpriseSmsQuotaChargeFailedException;
use App\Exceptions\EnterpriseSmsQuotaDecrFailedException;
use App\Exceptions\EnterpriseSmsQuotaLackedException;
use app\Exceptions\EnterpriseSmsRecordSaveFailedException;
use App\Exceptions\EnterpriseSmsSendFailedException;
use App\Libraries\SMS\Submail;
use App\Models\Enterprise;
use App\Repositories\EnterpriseSmsQuotaRepository;
use App\Repositories\EnterpriseSmsRecordRepository;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * 短信服务
 * Class SmsService
 * @author  JohnWang <takato@vip.qq.com>
 * @package app\Services
 */
class SmsService extends BaseService
{
    /**
     * @var EnterpriseSmsQuotaRepository
     */
    protected $enterpriseSmsQuotaRepository;

    /**
     * @var EnterpriseSmsRecordRepository
     */
    protected $enterpriseSmsRecordRepository;

    /**
     * SmsService constructor.
     * @param EnterpriseSmsQuotaRepository $enterpriseSmsQuotaRepository
     */
    public function __construct(EnterpriseSmsQuotaRepository $enterpriseSmsQuotaRepository,
                                EnterpriseSmsRecordRepository $enterpriseSmsRecordRepository)
    {
        $this->enterpriseSmsQuotaRepository = $enterpriseSmsQuotaRepository;
        $this->enterpriseSmsRecordRepository = $enterpriseSmsRecordRepository;
    }

    /**
     * 短信配额充值
     * @param            $num
     * @param Enterprise $enterprise
     * @throws EnterpriseSmsQuotaChargeFailedException
     * @author         JohnWang <takato@vip.qq.com>
     */
    public function charge($num, Enterprise $enterprise, $comment = '')
    {
        // 增加短信配额
        $result = $this->enterpriseSmsQuotaRepository->pushCriteria(new EnterpriseCriteria($enterprise))
            ->increment(
                'sms_quota',
                $num,
                ['total_sms_quota' => new Expression("total_sms_quota + $num")]
            );

        // 短信配额充值失败
        if ($result <= 0) {
            throw new EnterpriseSmsQuotaChargeFailedException();
        }

        // 获取企业短信配额
        $enterpriseSmsQuota = $this->enterpriseSmsQuotaRepository->pushCriteria(new EnterpriseCriteria($enterprise))
            ->all()
            ->first();

        // 触发充值成功事件
        event(new EnterpriseSmsQuotaWasCharged($enterpriseSmsQuota, $num, $comment));
    }

    /**
     * 人工扣减配额
     *
     * @param integer $num     扣减数量
     * @param Enterprise  $enterprise
     * @param string      $comment 备注
     * @throws EnterpriseSmsQuotaDecrFailedException
     * @author         JohnWang <takato@vip.qq.com>
     */
    public function decrease($num, Enterprise $enterprise, $comment = '')
    {
        // 检查短信配额
        $this->checkSmsQuota($enterprise);

        // 扣减短信配额
        $result = $this->enterpriseSmsQuotaRepository->pushCriteria(new EnterpriseCriteria($enterprise))
            ->decrement(
                'sms_quota',
                $num,
                ['total_sms_quota' => new Expression("total_sms_quota - $num")]
            );

        // 短信配额扣减失败
        if ($result <= 0) {
            throw new EnterpriseSmsQuotaDecrFailedException();
        }

        // 获取企业短信配额
        $enterpriseSmsQuota = $this->enterpriseSmsQuotaRepository->pushCriteria(new EnterpriseCriteria($enterprise))
            ->all()
            ->first();

        // 触发扣减配额事件
        event(new EnterpriseSmsQuotaWasDecreased($enterpriseSmsQuota, $num, $comment));
    }

    /**
     * 不需要企业 EPkey 的短信发送
     * @param $mobile
     * @param $tplId
     * @param array $params
     * @param array $options
     * @return bool
     * @throws EnterpriseSmsSendFailedException
     * @author: Zhengqian.zhu <zhuzhengqian@vchangyi.com>
     */
    public function generalSend($mobile, $tplId, $params = [], $options = [])
    {
        // 转换参数为集合对象
        if (is_array($params)) {
            $params = new Collection($params);
        }

        // 转换选项为集合对象
        if (is_array($options)) {
            $options = new Collection($options);
        }

        // 获取发送短信对象
        $sms = new Submail();

        // 获取发送短信结果
        try {
            $result = $sms->sendSms(
                $mobile,
                $tplId,
                $params->toArray()
            );
        } catch (\Exception $e) {
            // 短信发送失败
            throw new EnterpriseSmsSendFailedException('', 0, $e);
        }

        return true;

    }


    /**
     * 发送短信
     *
     * @param  string    $mobile
     * @param  string    $tplId   短信模板 ID
     * @param Enterprise $enterprise
     * @param array      $params  短信模板参数
     * @param array      $options 选项，unlimited 不检查短信配额
     * @throws EnterpriseSmsQuotaDecrFailedException
     * @throws EnterpriseSmsSendFailedException
     * @author         JohnWang <takato@vip.qq.com>
     */
    public function send($mobile, $tplId, Enterprise $enterprise, $params = [], $options = [])
    {
        // 转换参数为集合对象
        if (is_array($params)) {
            $params = new Collection($params);
        }

        // 转换选项为集合对象
        if (is_array($options)) {
            $options = new Collection($options);
        }

        // 检查是否开启无限制发送短信
        if (!$options->has('unlimited')) {
            // 检查短信配额
            $this->checkSmsQuota($enterprise);
        }

        // 获取发送短信对象
        $sms = new Submail();

        // 获取发送短信结果
        try {
            $result = $sms->sendSms(
                $mobile,
                $tplId,
                $params->toArray()
            );
        } catch (\Exception $e) {
            // 短信发送失败
            throw new EnterpriseSmsSendFailedException('', 0, $e);
        }

        return DB::transaction(function () use ($mobile, $tplId, $enterprise, $params, $result) {
            // 获取费用
            $num = isset($result['fee']) ? $result['fee'] : 1;

            // 扣减短信配额
            $decrResult = $this->enterpriseSmsQuotaRepository->pushCriteria(new EnterpriseCriteria($enterprise))
                ->decrement(
                    'sms_quota',
                    $num,
                    ['used_sms_quota' => new Expression("used_sms_quota + $num")]
                );

            // 短信配额扣减失败
            if ($decrResult <= 0) {
                throw new EnterpriseSmsQuotaDecrFailedException();
            }

            // 获取企业短信配额
            $enterpriseSmsQuota = $this->enterpriseSmsQuotaRepository->pushCriteria(new EnterpriseCriteria($enterprise))
                ->all()
                ->first();

            // 保存短信发送记录
            $enterpriseSmsRecord = $this->enterpriseSmsRecordRepository->create([
                'ep_key' => $enterprise->ep_key,
                'mobile' => $mobile,
                'content' => json_encode(array_merge($params->toArray(), ['tpl_id' => $tplId])),
                'send_id' => isset($result['send_id']) ? $result['send_id'] : 0,
                'num' => $num,
            ]);

            if (!$enterpriseSmsRecord) {
                // 保存短信发送记录失败
                throw new EnterpriseSmsRecordSaveFailedException();
            }

            // 触发发送短信事件
            event(new EnterpriseSmsWasSent($enterpriseSmsRecord, $enterpriseSmsQuota));
        });
    }

    /**
     * 检查短信配额
     * @param Enterprise $enterprise
     * @throws EnterpriseSmsQuotaLackedException
     * @author         JohnWang <takato@vip.qq.com>
     */
    protected function checkSmsQuota(Enterprise $enterprise)
    {
        // 获取企业短信配额
        $enterpriseSmsQuota = $this->enterpriseSmsQuotaRepository->pushCriteria(new EnterpriseCriteria($enterprise))
            ->all()
            ->first();

        // 短信配额不足抛出错误
        if (!$enterpriseSmsQuota || $enterpriseSmsQuota->sms_quota <= 0) {
            throw new EnterpriseSmsQuotaLackedException();
        }
    }
}