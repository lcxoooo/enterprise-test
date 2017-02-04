<?php

namespace App\Repositories;

use App\Models\WechatCorpApp;
use App\Criteria\RequestCriteria;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class WechatCorpAppRepositoryEloquent
 * @package namespace App\Repositories;
 */
class WechatCorpAppRepositoryEloquent extends BaseRepositoryEloquent implements WechatCorpAppRepository
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE=>[
            'ep_key'=>'required',
            'app_id'=>'required',
            'type'=>'required',
            'agent_id'=>'required'
        ],

        ValidatorInterface::RULE_UPDATE=>[
            'ep_key'=>'required',
            'app_id'=>'required',
            'type'=>'required',
            'agent_id'=>'required'
        ],

    ];


    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WechatCorpApp::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
