<?php
namespace App\Events;

use App\Models\Enterprise;
use Illuminate\Queue\SerializesModels;

/**
 * Class EnterpriseWasDisable
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Events
 */
class EnterpriseWasDisable extends Event
{
    use SerializesModels;

    public $enterprise;
    public $type = 'disable';

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