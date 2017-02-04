<?php namespace App\Services;

use App\Events\EnterpriseRegister;
use App\Exceptions\ChangyiAuth\EpKeyExistException;
use App\Modules\ChangyiAuth\Enterprise as ChangyiAuthEnterprise;
use App\Modules\ChangyiAuth\EnterpriseAttributesInterface;
use App\Repositories\EnterpriseRepositoryEloquent;
use App\Repositories\EnterpriseSmsQuotaRepositoryEloquent;

/**
 * Created by Zhengqian.Zhu
 * Email: zhuzhengqian@vchangyi.com
 * Date: 2016/10/31
 */
class EnterpriseService extends BaseService
{

    protected $enterpriseRepositoryEloquent;

    protected $enterpriseSmsQuotaEloquent;

    public function __construct(EnterpriseRepositoryEloquent $enterpriseRepositoryEloquent , EnterpriseSmsQuotaRepositoryEloquent $enterpriseSmsQuotaEloquent)
    {
        $this->enterpriseRepositoryEloquent = $enterpriseRepositoryEloquent;
        $this->enterpriseSmsQuotaEloquent   = $enterpriseSmsQuotaEloquent;
    }


    /**
     * 企业注册
     * @param \App\Modules\ChangyiAuth\EnterpriseAttributesInterface $enterpriseAttribute
     * @param string                                                 $channel
     * @return mixed
     * @throws \App\Exceptions\ChangyiAuth\CompanyIllegalException
     * @throws \App\Exceptions\ChangyiAuth\EmailExistException
     * @throws \App\Exceptions\ChangyiAuth\EmailIllegalException
     * @throws \App\Exceptions\ChangyiAuth\EpKeyExistException
     * @throws \App\Exceptions\ChangyiAuth\EpKeyIllegalException
     * @throws \App\Exceptions\ChangyiAuth\MobileExistException
     * @throws \App\Exceptions\ChangyiAuth\MobileIllegalException
     * @throws \App\Exceptions\ChangyiAuth\RealNameIllegalException
     * @throws \App\Exceptions\ChangyiAuth\RequestOAServerException
     * @throws \Exception
     * @author zhuzhengqian@vchangyi.com
     */
    public function register(EnterpriseAttributesInterface $enterpriseAttribute, $channel = "UNKNOWN")
    {
        $enterprise = $this->enterpriseRepositoryEloquent->findWhere([
            'ep_key'=>$enterpriseAttribute->getEpKey()
                                                                     ])
            ->first();

        if($enterprise){
            //如果已经存在 epKey
            throw new EpKeyExistException($enterpriseAttribute->getEpKey() . " Exist");
        }

        ChangyiAuthEnterprise::register($enterpriseAttribute);
        //写入数据库
        $enterprise = $this->enterpriseRepositoryEloquent->create([
                                                                      'ep_key'        => $enterpriseAttribute->getEpKey() ,
                                                                      'email'         => $enterpriseAttribute->getEmail() ,
                                                                      'mobile'        => $enterpriseAttribute->getMobile() ,
                                                                      'realname'      => $enterpriseAttribute->getRealName() ,
                                                                      'company_name'  => $enterpriseAttribute->getCompanyName() ,
                                                                      'register_from' => $channel
                                                                  ]);



        return $enterprise;
    }


}