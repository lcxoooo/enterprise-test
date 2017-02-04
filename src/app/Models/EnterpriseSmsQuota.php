<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class EnterpriseSmsQuota
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Models
 */
class EnterpriseSmsQuota extends BaseModel
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'sms_quota'       => 'integer',
        'used_sms_quota'  => 'integer',
        'total_sms_quota' => 'integer',
    ];

    protected $fillable = [
        'ep_key',
        'sms_quota',
        'used_sms_quota',
        'total_sms_quota',
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

}
