<?php namespace App\Events;
use App\Models\Enterprise;
use Illuminate\Queue\SerializesModels;

/**
 * Created by Zhengqian.Zhu
 * Email: zhuzhengqian@vchangyi.com
 * Date: 2016/11/1
 */
class EnterpriseRegister extends Event
{
    use SerializesModels;

    public $enterprise;
    public $type = 'register';

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