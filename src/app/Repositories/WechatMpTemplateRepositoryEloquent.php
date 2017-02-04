<?php

namespace App\Repositories;

use App\Models\WechatMpTemplate;
use App\Criteria\RequestCriteria;

/**
 * Class WechatMpTemplateRepositoryEloquent
 * @package namespace App\Repositories;
 */
class WechatMpTemplateRepositoryEloquent extends BaseRepositoryEloquent implements WechatMpTemplateRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WechatMpTemplate::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
