<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class WechatMp
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Models
 */
class WechatMp extends BaseModel
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    const STATUS_NORMAL = 'NORMAL';
    const STATUS_DISABLE = 'DISABLE';

    protected $casts = [
        'is_edit_menu_item' => 'boolean'
    ];

    protected $fillable = [
        'ep_key',
        'app_id',
        'app_secret',
        'server_token',
        'encoding_aes_key',
        'component_refresh_token',
        'authorize_type',
        'nick_name',
        'head_img',
        'original_id',
        'alias',
        'qrcode_url',
        'status',
        'is_edit_menu_item',
        'created_at'
    ];

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wechatMpTemplates()
    {
        return $this->hasMany(WechatMpTemplate::class, 'ep_key', 'ep_key');
    }

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wechatMpMenuEvents()
    {
        return $this->hasMany(WechatMpMenuEvent::class, 'ep_key', 'ep_key');
    }

}
