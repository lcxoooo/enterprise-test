<?php
namespace App\Events;

use App\Models\Enterprise;
use Illuminate\Queue\SerializesModels;

/**
 * Class EnterpriseWasModified
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Events
 */
class EnterpriseWasModified extends Event
{
    use SerializesModels;

    public $enterprise;
    public $type = 'enterprise-modify';

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