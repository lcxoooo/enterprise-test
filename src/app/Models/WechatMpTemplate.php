<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class WechatMpTemplate
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Models
 */
class WechatMpTemplate extends BaseModel
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [];

    protected $fillable = [
        'ep_key',
        'type',
        'tpl_id',
        'created_at'
    ];

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wechatMp()
    {
        return $this->hasOne(WechatMp::class, 'ep_key', 'ep_key');
    }
}
