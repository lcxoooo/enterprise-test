<?php

namespace App\Repositories;

use App\Criteria\RequestCriteria;
use App\Models\EnterpriseSmsQuotaLog;

/**
 * Class EnterpriseSmsQuotaLogRepositoryEloquent
 * @package namespace App\Repositories;
 */
class EnterpriseSmsQuotaLogRepositoryEloquent extends BaseRepositoryEloquent implements EnterpriseSmsQuotaLogRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return EnterpriseSmsQuotaLog::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
