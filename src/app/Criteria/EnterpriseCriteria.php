<?php

namespace App\Criteria;

use App\Models\Enterprise;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class EnterpriseCriteria
 * @package namespace App\Criteria;
 */
class EnterpriseCriteria implements CriteriaInterface
{
    /**
     * @var Enterprise
     */
    protected $enterprise;

    /**
     * EnterpriseCriteria constructor.
     * @param Enterprise $enterprise
     */
    public function __construct(Enterprise $enterprise)
    {
        $this->enterprise = $enterprise;
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
        return $model->where('ep_key', $this->enterprise->ep_key);
    }
}
