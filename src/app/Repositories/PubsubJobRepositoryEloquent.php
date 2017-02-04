<?php

namespace App\Repositories;

use App\Libraries\Validator\Contracts\ValidatorInterface;
use App\Models\PubsubJob;
use App\Criteria\RequestCriteria;

/**
 * Class PubsubJobRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PubsubJobRepositoryEloquent extends BaseRepositoryEloquent implements PubsubJobRepository
{
    /**
     * Specify Validator Rules
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'type' => 'required|in:' . PubsubJob::TYPE_PUB . ',' . PubsubJob::TYPE_SUB,
            'channel' => 'required|string',
            'offset' => 'required|numeric',
            'payload' => 'string',
            'status' => 'required|in:' . PubsubJob::STATUS_PROCESSING . ',' . PubsubJob::STATUS_SUCCEEDED . ',' . PubsubJob::STATUS_FAILED,
            'retry_times' => 'sometimes|numeric',
            'failed_reason' => 'sometimes|string',
        ],
        ValidatorInterface::RULE_UPDATE => [

        ]
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PubsubJob::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
