<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/11/14
 * Time: 16:15
 */

namespace App\Criteria;


use App\Models\PubsubJob;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class PubsubJobCriteria implements CriteriaInterface
{
    /**
     * @var PubsubJob
     */
    protected $pubsubJob;

    /**
     * PosMachineCriteria constructor.
     * @param PubsubJob $pubsubJob
     */
    public function __construct(PubsubJob $pubsubJob)
    {
        $this->pubsubJob = $pubsubJob;
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
        return $model->where('id', $this->pubsubJob->id);
    }
}
