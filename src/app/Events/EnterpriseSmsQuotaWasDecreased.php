<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/11/3
 * Time: 15:33
 */

namespace App\Events;
use App\Models\EnterpriseSmsQuota;
use Illuminate\Queue\SerializesModels;

/**
 * Class EnterpriseSmsQuotaWasDecreased
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Events
 */
class EnterpriseSmsQuotaWasDecreased extends Event
{
    use SerializesModels;

    /**
     * @var EnterpriseSmsQuota
     */
    public $enterpriseSmsQuota;

    /**
     * @var int
     */
    public $num;

    /**
     * @var string
     */
    public $comment;

    /**
     * EnterpriseSmsQuotaWasCharged constructor.
     * @param EnterpriseSmsQuota $enterpriseSmsQuota
     * @param integer $num
     * $param string $comment
     */
    public function __construct(EnterpriseSmsQuota $enterpriseSmsQuota, $num, $comment = '')
    {
        $this->enterpriseSmsQuota = $enterpriseSmsQuota;
        $this->num = $num;
        $this->comment = $comment ?: '扣减';
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

}