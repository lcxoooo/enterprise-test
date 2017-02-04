<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/11/3
 * Time: 15:34
 */

namespace App\Events;

use App\Models\EnterpriseSmsQuota;
use App\Models\EnterpriseSmsRecord;
use Illuminate\Queue\SerializesModels;

/**
 * Class EnterpriseSmsWasSent
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Events
 */
class EnterpriseSmsWasSent extends Event
{
    use SerializesModels;

    /**
     * @var EnterpriseSmsRecord
     */
    public $enterpriseSmsRecord;

    /**
     * @var EnterpriseSmsQuota
     */
    public $enterpriseSmsQuota;

    /**
     * @var string
     */
    public $comment;

    /**
     * EnterpriseSmsWasSent constructor.
     * @param EnterpriseSmsRecord $enterpriseSmsRecord
     * @param EnterpriseSmsQuota  $enterpriseSmsQuota
     * @param string              $comment
     */
    public function __construct(EnterpriseSmsRecord $enterpriseSmsRecord, EnterpriseSmsQuota $enterpriseSmsQuota, $comment = '')
    {
        $this->enterpriseSmsRecord = $enterpriseSmsRecord;
        $this->enterpriseSmsQuota = $enterpriseSmsQuota;
        $this->comment = $comment ?: '发送短信';
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