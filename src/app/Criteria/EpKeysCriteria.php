<?php

namespace App\Criteria;

use App\Models\Enterprise;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class EnterpriseCriteria
 * @package namespace App\Criteria;
 */
class EpKeysCriteria implements CriteriaInterface
{
    /**
     * @var Enterprise
     */
    protected $epKeys;

    /**
     * EnterpriseCriteria constructor.
     * @param Enterprise $enterprise
     */
    public function __construct($epKeys)
    {
        $this->epKeys = $epKeys;
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
        return $model->whereIn('ep_key',$this->epKeys);
    }
}
