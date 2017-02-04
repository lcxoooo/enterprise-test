<?php

namespace App\Criteria;

use App\Models\Enterprise;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class EnterpriseCriteria
 * @package namespace App\Criteria;
 */
class CreatedTimeCriteria implements CriteriaInterface
{
    /**
     * @var Enterprise
     */
    protected $createdTime;

    protected $compareOperate;


    /**
     * EnterpriseCriteria constructor.
     * @param Enterprise $enterprise
     */
    public function __construct($createdTime, $compareOperate = '=')
    {
        $this->createdTime = $createdTime;
        $this->compareOperate = $compareOperate;
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
        return $model->where('created_at', $this->compareOperate, $this->createdTime);
    }
}
