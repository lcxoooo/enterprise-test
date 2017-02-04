<?php

namespace App\Repositories;

use App\Criteria\RequestCriteria;
use App\Repositories\EnterpriseSmsRecordRepository;
use App\Models\EnterpriseSmsRecord;

/**
 * Class EnterpriseSmsRecordRepositoryEloquent
 * @package namespace App\Repositories;
 */
class EnterpriseSmsRecordRepositoryEloquent extends BaseRepositoryEloquent implements EnterpriseSmsRecordRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return EnterpriseSmsRecord::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
