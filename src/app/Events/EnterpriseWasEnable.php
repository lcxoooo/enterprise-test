<?php
namespace App\Events;

use App\Models\Enterprise;
use Illuminate\Queue\SerializesModels;

/**
 * Class EnterpriseWasEnable
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Events
 */
class EnterpriseWasEnable extends Event
{
    use SerializesModels;

    public $enterprise;
    public $type = 'enable';

    public function __construct(Enterprise $enterprise)
    {
        $this->enterprise = $enterprise;
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