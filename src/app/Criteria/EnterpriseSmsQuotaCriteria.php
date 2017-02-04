<?php

namespace App\Criteria;

use App\Models\EnterpriseSmsQuota;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class EnterpriseSmsQuotaCriteria
 * @package namespace App\Criteria;
 */
class EnterpriseSmsQuotaCriteria implements CriteriaInterface
{
    /**
     * @var EnterpriseSmsQuota
     */
    protected $enterpriseSmsQuota;

    /**
     * EnterpriseCriteria constructor.
     * @param EnterpriseSmsQuota $enterprise
     */
    public function __construct(EnterpriseSmsQuota $enterpriseSmsQuota)
    {
        $this->enterpriseSmsQuota = $enterpriseSmsQuota;
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
        return $model->where('ep_key', $this->enterpriseSmsQuota->ep_key);
    }
}
