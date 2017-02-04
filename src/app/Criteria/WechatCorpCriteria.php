<?php

namespace App\Criteria;

use App\Models\WechatCorp;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class WechatCorpCriteria
 * @package namespace App\Criteria;
 */
class WechatCorpCriteria implements CriteriaInterface
{
    /**
     * @var WechatCorp
     */
    protected $wechatCorp;

    /**
     * EnterpriseCriteria constructor.
     * @param WechatCorp $enterprise
     */
    public function __construct(WechatCorp $wechatCorp)
    {
        $this->wechatCorp = $wechatCorp;
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
        return $model->where('ep_key', $this->wechatCorp->ep_key);
    }
}
