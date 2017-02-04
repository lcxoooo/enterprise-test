<?php

namespace App\Repositories;

use App\Models\EnterpriseSmsQuota;
use App\Criteria\RequestCriteria;

/**
 * Class EnterpriseSmsQuotaRepositoryEloquent
 * @package namespace App\Repositories;
 */
class EnterpriseSmsQuotaRepositoryEloquent extends BaseRepositoryEloquent implements EnterpriseSmsQuotaRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return EnterpriseSmsQuota::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
