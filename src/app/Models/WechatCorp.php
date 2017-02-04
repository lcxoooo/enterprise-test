<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class WechatCorp
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Models
 */
class WechatCorp extends BaseModel
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    const STATUS_NORMAL = 'NORMAL';
    const STATUS_DISABLE = 'DISABLE';

    protected $casts = [];

    protected $fillable = [
        'ep_key',
        'corp_id',
        'corp_secret',
        'auth_info',
        'auth_fail_reason',
        'corp_name',
        'corp_round_logo_url',
        'corp_square_logo_ur',
        'qrcode_url',
        'chat_secret',
        'chat_token',
        'chat_encoding_aes_key',
        'status',
        'created_at'
    ];

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wechatCorpApps()
    {
        return $this->hasMany(WechatCorpApp::class, 'ep_key', 'ep_key');
    }



}

