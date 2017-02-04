<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * 企业短信配置修改记录
 * Class EnterpriseSmsQuotaLog
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Models
 */
class EnterpriseSmsQuotaLog extends BaseModel
{
    const TYPE_CONSUME  = 'CONSUME';     // 消费
    const TYPE_CHARGE   = 'CHARGE';       // 充值
    const TYPE_DECREASE = 'DECREASE';   // 人工减少

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'num'       => 'integer',
        'record_id' => 'integer',
    ];

    protected $fillable = [
        'ep_key',
        'type',
        'num',
        'comment',
        'record_id',
        'created_at',
    ];

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function enterprise()
    {
        return $this->hasOne(Enterprise::class, 'ep_key', 'ep_key');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author         JohnWang <takato@vip.qq.com>
     */
    public function enterpriseSmsRecord()
    {
        return $this->hasOne(EnterpriseSmsRecord::class, 'id', 'record_id');
    }
}
