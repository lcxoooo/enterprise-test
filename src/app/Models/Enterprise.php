<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Enterprise
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Models
 */
class Enterprise extends BaseModel
{

    const ENTERPRISE_STATUS_NORMAL = 'NORMAL';
    const ENTERPRISE_STATUS_DISABLE = 'DISABLE';


    const TYPE_PROBATION = 'PROBATION';//使用
    const TYPE_PAY = 'PAY';//付费
    const TYPE_PERMANENT = 'PERMANENT';//永久

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ep_key';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $dates = [
        'created_at',
        'updated_at',
        'store_expires_at',
        'cashier_expires_at'
    ];

    protected $casts = [];

    protected $fillable = [
        'ep_key',
        'email',
        'mobile',
        'realname',
        'company_name',
        'logo_url',
        'store_on',
        'cashier_on',
        'seller_mode_on',
        'store_type',
        'store_expires_at',
        'cashier_type',
        'cashier_expires_at',
        'owned_max_store',
        'owned_max_guide',
        'level',
        'erp_on',
        'status'
    ];

    /**
     * @author Zhengqian.zhu
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function enterpriseSmsQuota()
    {
        return $this->hasOne(EnterpriseSmsQuota::class, 'ep_key', 'ep_key');
    }

}
