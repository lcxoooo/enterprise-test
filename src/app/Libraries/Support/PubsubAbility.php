<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/11/14
 * Time: 15:04
 */

namespace App\Libraries\Support;


use App\Criteria\PubsubJobCriteria;
use App\Models\PubsubJob;
use App\Repositories\PubsubJobRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Superbalist\PubSub\PubSubAdapterInterface;
use Takatost\LumenPubSub\PubSubConnectionFactory;

/**
 * Class PubsubAbility
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Libraries\Support
 */
trait PubsubAbility
{
    /**
     * @var PubSubAdapterInterface
     */
    protected $pubsub;

    /**
     * @var PubsubJobRepository
     */
    protected $pubsubJobRepository;

    /**
     * @param          $channel
     * @param callable $handler
     * @author         JohnWang <takato@vip.qq.com>
     */
    public function subscribe($channel, callable $handler)
    {
        $this->pubsubJobRepository = app(PubsubJobRepository::class);
        $factory = app(PubSubConnectionFactory::class);

        $config = config('pubsub.connections.kafka');
        $config['consumer_group_id'] = config('pubsub.connections.kafka.consumer_group_id') . '.' . self::class;
        $this->pubsub = $factory->make('kafka', $config);

        $this->pubsub->subscribe($channel, function ($payload, $msgResult) use ($channel, $handler) {
            // 查询是否有记录
            $job = $this->pubsubJobRepository->findWhere([
                'type'    => PubsubJob::TYPE_SUB,
                'channel' => $channel,
                'offset'  => $msgResult->offset
            ])
                ->first();

            if (!$job) {
                // 首次则记录订阅推送事件内容
                $job = $this->pubsubJobRepository->create([
                    'type'    => PubsubJob::TYPE_SUB,
                    'channel' => $channel,
                    'offset'  => $msgResult->offset,
                    'payload' => $msgResult->payload,
                    'status'  => PubsubJob::STATUS_PROCESSING
                ]);
            } else {
                // 上次失败重试
                if ($job->status !== PubsubJob::STATUS_FAILED) {
                    return;
                } else if ($job->retry_times >= config('pubsub.max_retry_times')) {
                    return;
                }

                // 更新为进行中状态
                $job = $this->pubsubJobRepository->update(
                    ['status' => PubsubJob::STATUS_PROCESSING],
                    $job->id
                );
            }

            try {
                call_user_func($handler, $payload, $msgResult);
            } catch (\Exception $e) {
                $job->retry_times++;
                $job->status = PubsubJob::STATUS_FAILED;
                $job->failed_reason = $job->failed_reason . '[' . $e->getCode() . '] ' . $e->getMessage() . ';';

                // 记录任务执行失败状态
                $this->pubsubJobRepository->pushCriteria(new PubsubJobCriteria($job))
                    ->increment(
                        'retry_times',
                        1,
                        [
                            'status'        => $job->status,
                            'failed_reason' => $job->failed_reason
                        ]
                    );

                if ($job->retry_times >= config('pubsub.max_retry_times')) {
                    // 重试超过次数，通知维护人员
                    $this->sendWarn($job);
                }

                throw $e;
            }

            // 更新为成功状态
            $this->pubsubJobRepository->update(
                ['status' => PubsubJob::STATUS_SUCCEEDED],
                $job->id
            );
        });
    }

    /**
     * 发送云维警告
     * @param PubsubJob $pubsubJob
     * @author         JohnWang <takato@vip.qq.com>
     */
    protected function sendWarn(PubsubJob $pubsubJob)
    {
        $subject = '【' . env('APP_ENV') . '】发布订阅事件[ID: ' . $pubsubJob->id . ']执行失败次数超过限制';

        $data = [
            'type'          => $pubsubJob->type === PubsubJob::TYPE_SUB ? '订阅' : '发布',
            'channel'       => $pubsubJob->channel,
            'offset'        => $pubsubJob->offset,
            'payload'       => $pubsubJob->payload,
            'retry_times'   => $pubsubJob->retry_times,
            'failed_reason' => $pubsubJob->failed_reason,
            'time'          => Carbon::now()->toDateTimeString(),
            'footer'        => config('api.name')
        ];

        $emailStr = config('pubsub.warn_receivers');
        if (!$emailStr) {
            return;
        }

        // 分解 Email 列表
        $emails = explode(',', $emailStr);
        if (!$emails) {
            return;
        }

        // 发送邮件
        Mail::send('mails.pubsub_retry_over_limited', $data, function ($message) use ($emails, $subject) {
            $message->from(config('mail.username'), config('mail.user_title'))
                ->to($emails, $emails)
                ->subject($subject);
        });
    }
}