<?php

namespace App\Repositories;

use App\Models\WechatMp;
use App\Criteria\RequestCriteria;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class WechatMpRepositoryEloquent
 * @package namespace App\Repositories;
 */
class WechatMpRepositoryEloquent extends BaseRepositoryEloquent implements WechatMpRepository
{


    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'ep_key'=>'required',
            'app_id'=>'required',
            'component_refresh_token'=>'required',
            'nick_name'=>'required',
            'head_img'=>'required',
            'original_id'=>'required',
            'alias'=>'required',
            'qrcode_url'=>'required'
        ],
        ValidatorInterface::RULE_CREATE => [

        ]
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WechatMp::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
