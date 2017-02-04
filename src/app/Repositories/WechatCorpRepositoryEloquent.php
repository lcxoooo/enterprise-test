<?php

namespace App\Repositories;

use App\Models\WechatCorp;
use App\Criteria\RequestCriteria;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class WechatCorpRepositoryEloquent
 * @package namespace App\Repositories;
 */
class WechatCorpRepositoryEloquent extends BaseRepositoryEloquent implements WechatCorpRepository
{

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'ep_key'=>'required',
            'corp_id'=>'required',
            'permanent_code'=>'required',
            'auth_info'=>'required',
            'corp_name'=>'required',
            'corp_round_logo_url'=>'required',
            'corp_square_logo_url'=>'required',
            'qrcode_url'=>'required',
            'chat_secret'=>'',
            'chat_token'=>'',
            'chat_encoding_aes_key'=>''
        ],
        ValidatorInterface::RULE_UPDATE => [

        ],
    ];


    protected $messages = [
        'ep_key.required'=>'企业标示必须设置',
        'corp_id.required'=>'企业corp_id必须设置',
        'permanent_code.required'=>'permanent_code必须设置',
        'auth_info.required'=>'auth_info 必须设置',
        'corp_name.required'=>'企业名称 必须设置',
        'corp_round_logo_url.required'=>'企业圆头像 必须设置',
        'corp_square_logo_url.required'=>'企业方头像 必须设置',
        'qrcode_url.required'=>'企业号二维码必须设置'
    ];


    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WechatCorp::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
