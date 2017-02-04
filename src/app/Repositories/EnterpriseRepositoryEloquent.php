<?php

namespace App\Repositories;

use App\Criteria\RequestCriteria;
use App\Models\Enterprise;
use Prettus\Validator\Contracts\ValidatorInterface;

/**
 * Class EnterpriseRepositoryEloquent
 * @package namespace App\Repositories;
 */
class EnterpriseRepositoryEloquent extends BaseRepositoryEloquent implements EnterpriseRepository
{
    /**
     * Specify Validator Rules
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'mobile'       => 'required|regex:/^1[0-9]{10}$/',
            'email'        => 'required|email',
            'company_name' => 'required|string',
            'realname'     => 'required|string',
            'logo_url'     => 'sometimes|string'
        ],
        ValidatorInterface::RULE_UPDATE => [
        ]
    ];

    protected $messages = [
        'mobile.required' => '手机号码必填',
        'email.email' => '邮箱格式不正确',
        'company_name.string' => '公司名称不正确',
        'realname.string' => '真实姓名不正确',
        'logo_url.string' => 'LOGO 地址不正确',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Enterprise::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
