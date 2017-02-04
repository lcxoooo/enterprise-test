<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class WechatTicket
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Models
 */
class WechatTicket extends BaseModel
{
    /**
     * 类型
     */
    const TYPE_MP   = 'MP';     // 服务号
    const TYPE_CORP = 'CORP';   // 企业号

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [];

    protected $fillable = [
        'type',
        'ticket',
        'created_at'
    ];

}
