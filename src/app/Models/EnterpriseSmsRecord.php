<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class EnterpriseSmsRecord
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Models
 */
class EnterpriseSmsRecord extends BaseModel
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'num' => 'integer'
    ];

    protected $fillable = [
        'ep_key',
        'mobile',
        'content',
        'send_id',
        'num',
        'created_at'
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
     * @author JohnWang <takato@vip.qq.com>
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function enterpriseSmsQuota()
    {
        return $this->hasOne(EnterpriseSmsQuota::class, 'ep_key', 'ep_key');
    }

}
