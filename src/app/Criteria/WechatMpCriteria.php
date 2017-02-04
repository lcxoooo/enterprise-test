<?php

namespace App\Criteria;

use App\Models\WechatMp;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class WechatMpCriteria
 * @package namespace App\Criteria;
 */
class WechatMpCriteria implements CriteriaInterface
{
    /**
     * @var WechatMp
     */
    protected $wechatMp;

    /**
     * EnterpriseCriteria constructor.
     * @param WechatMp $enterprise
     */
    public function __construct(WechatMp $wechatMp)
    {
        $this->wechatMp = $wechatMp;
    }

    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('ep_key', $this->wechatMp->ep_key);
    }
}
