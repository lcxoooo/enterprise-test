<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class EnterpriseSetting
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Models
 */
class EnterpriseSetting extends BaseModel
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [];

    protected $fillable = [
        'ep_key',
        'key',
        'value',
        'description',
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
