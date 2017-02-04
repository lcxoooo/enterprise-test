<?php

namespace App\Repositories;

use App\Models\WechatTicket;
use App\Criteria\RequestCriteria;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class WechatTicketRepositoryEloquent
 * @package namespace App\Repositories;
 */
class WechatTicketRepositoryEloquent extends BaseRepositoryEloquent implements WechatTicketRepository
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'type'   => 'required' ,
            'ticket' => 'required' ,
        ] ,
        ValidatorInterface::RULE_UPDATE => [
            'ticket' => 'required'
        ]
    ];


    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WechatTicket::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
