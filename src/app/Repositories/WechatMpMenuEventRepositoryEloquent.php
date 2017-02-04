<?php

namespace App\Repositories;

use App\Models\WechatMpMenuEvent;
use App\Criteria\RequestCriteria;

/**
 * Class WechatMpMenuEventRepositoryEloquent
 * @package namespace App\Repositories;
 */
class WechatMpMenuEventRepositoryEloquent extends BaseRepositoryEloquent implements WechatMpMenuEventRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WechatMpMenuEvent::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
