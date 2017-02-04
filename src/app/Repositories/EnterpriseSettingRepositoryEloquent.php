<?php

namespace App\Repositories;

use App\Criteria\RequestCriteria;
use App\Repositories\EnterpriseSettingRepository;
use App\Models\EnterpriseSetting;

/**
 * Class EnterpriseSettingRepositoryEloquent
 * @package namespace App\Repositories;
 */
class EnterpriseSettingRepositoryEloquent extends BaseRepositoryEloquent implements EnterpriseSettingRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return EnterpriseSetting::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
