<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class PubsubJob
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Models
 */
class PubsubJob extends BaseModel
{

    // 类型
    const TYPE_PUB = 'PUB'; // 发布
    const TYPE_SUB = 'SUB'; // 订阅

    // 状态
    const STATUS_PROCESSING = 'PROCESSING'; // 处理中
    const STATUS_SUCCEEDED  = 'SUCCEEDED'; // 处理成功
    const STATUS_FAILED     = 'FAILED'; // 处理失败


    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'offset'      => 'integer',
        'retry_times' => 'integer'
    ];

    protected $fillable = [
        'type',
        'channel',
        'offset',
        'payload',
        'status',
        'retry_times',
        'failed_reason',
    ];

}
