<?php
namespace App\Events;

use App\Models\EnterpriseSmsQuota;
use Illuminate\Queue\SerializesModels;

/**
 * Class EnterpriseSmsQuotaWasCharged
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Events
 */
class EnterpriseSmsQuotaWasCharged extends Event
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
        $this->comment = $comment ?: '充值';
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