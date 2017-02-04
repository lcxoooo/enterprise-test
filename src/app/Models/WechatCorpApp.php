<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class WechatCorpApp
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Models
 */
class WechatCorpApp extends BaseModel
{

    const TYPE_MICRO_STORE = 'MICRO_STORE';//微店

    const TYPE_BOSS_ASSISTANT = 'BOSS_ASSISTANT';//老板助手

    const TYPE_CASHIER_BONUS = 'CASHIER_BONUS';//收银员福利社

    const TYPE_CUSTOMER_MANAGER = 'CUSTOMER_MANAGER';//客户管理

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    static $corpAppTypes = [
        self::TYPE_BOSS_ASSISTANT,
        self::TYPE_CASHIER_BONUS,
        self::TYPE_CUSTOMER_MANAGER,
        self::TYPE_MICRO_STORE
    ];

    const STATUS_NORMAL = 'NORMAL';

    protected $casts = [
        'app_id' => 'integer'
    ];

    protected $fillable = [
        'ep_key',
        'app_id',
        'type',
        'agent_id',
        'token',
        'encoding_aes_key',
        'permanent_code',
        'authorize_type',
        'description',
        'status',
        'created_at'
    ];

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wechatCorp()
    {
        return $this->hasOne(WechatCorp::class, 'ep_key', 'ep_key');
    }

}
