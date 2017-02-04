<?php namespace App\Modules\ChangyiAuth;

use App\Exceptions\ChangyiAuth\CompanyExistException;
use App\Exceptions\ChangyiAuth\CompanyIllegalException;
use App\Exceptions\ChangyiAuth\EmailExistException;
use App\Exceptions\ChangyiAuth\EmailIllegalException;
use App\Exceptions\ChangyiAuth\EpKeyExistException;
use App\Exceptions\ChangyiAuth\EpKeyIllegalException;
use App\Exceptions\ChangyiAuth\MobileExistException;
use App\Exceptions\ChangyiAuth\MobileIllegalException;
use App\Exceptions\ChangyiAuth\RealNameIllegalException;
use App\Exceptions\ChangyiAuth\RequestEnterpriseDetailException;
use App\Exceptions\ChangyiAuth\RequestEnterpriseListException;
use App\Exceptions\ChangyiAuth\RequestOAServerException;

/**
 * Created by Zhengqian.Zhu
 * Email: zhuzhengqian@vchangyi.com
 * Date: 16/4/28
 */
class Enterprise
{


    const API_LIST = "http://uc.vchangyi.com/PubApi/Api/Enterprise/List";

    const API_DETAIL = "http://uc.vchangyi.com/PubApi/Api/Enterprise/Fetch";

    const API_CHECK_MOBILE = "http://uc.vchangyi.com/PubApi/Api/Enterprise/CheckMobile";

    const API_CHECK_CORP = "http://uc.vchangyi.com/PubApi/Api/Enterprise/CheckCorpID";

    const API_REGISTER = "http://uc.vchangyi.com/PubApi/Api/Enterprise/RegHaomai";

    const API_CHECK_EP_NAME = "http://uc.vchangyi.com/PubApi/Api/Enterprise/CheckEpname";

    const API_CHECK_EPKEY = 'http://uc.vchangyi.com/PubApi/Api/Enterprise/CheckEnumber';

    /**
     * 企业列表
     * @param null $epId
     * @param null $cname
     * @param null $companyname
     * @param null $mobile
     * @param null $email
     * @param null $page
     * @param null $perpage
     * @return mixed
     * @throws \App\Exceptions\ChangyiAuth\RequestEnterpriseListException
     * @throws \App\Exceptions\ChangyiAuth\RequestOAServerException
     * @author zhuzhengqian@vchangyi.com
     */
    public static function lists($epId = null , $cname = null , $companyname = null , $mobile = null , $email = null , $page = null , $perpage = null)
    {
        $requestArr = [];
        $epId && $requestArr['ep_id'] = $epId;
        $cname && $requestArr['cname'] = $cname;
        $companyname && $requestArr['companyname'] = $companyname;
        $mobile && $requestArr['mobile'] = $mobile;
        $email && $requestArr['email'] = $email;
        $page && $requestArr['page'] = $page;
        $perpage && $requestArr['perpage'] = $perpage;
        $authKey = Util::getAuthKey();

        $time = time();
        $sig  = Util::generateSig($requestArr , $time , $authKey);

        try {
            $request = \Requests::request(self::API_LIST , [] , array_merge($requestArr , [
                'ts'  => $time ,
                'sig' => $sig
            ]));
        } catch (\Exception $e) {
            throw new RequestOAServerException;
        }

        $body = json_decode($request->body);
        if ($body->errcode != 0) {
            throw new RequestEnterpriseListException($body->errmsg , $body->errcode);
        }

        return $body->result->list;

    }


    /**
     * 企业详情
     * @param null $epId
     * @param null $mobile
     * @param null $cname
     * @return mixed
     * @throws \App\Exceptions\ChangyiAuth\RequestEnterpriseDetailException
     * @throws \App\Exceptions\ChangyiAuth\RequestOAServerException
     * @author zhuzhengqian@vchangyi.com
     */
    public static function detail($epId = null , $mobile = null , $cname = null)
    {
        $requestArr = [];
        $epId && $requestArr['ep_id'] = $epId;
        $mobile && $requestArr['mobile'] = $mobile;
        $cname && $requestArr['cname'] = $cname;
        $authKey = Util::getAuthKey();

        $time = time();
        $sig  = Util::generateSig($requestArr , $time , $authKey);

        try {
            $request = \Requests::request(self::API_DETAIL , [] , array_merge($requestArr , [
                'ts'  => $time ,
                'sig' => $sig
            ]),\Requests::GET);
        } catch (\Exception $e) {
            throw new RequestOAServerException;
        }

        $body = json_decode($request->body);

        if ($body->errcode != 0) {
            throw new RequestEnterpriseDetailException($body->errmsg , $body->errcode);
        }

        return $body->result->enterprise;
    }

    /**
     * 手机号是否注册
     * @param $mobile
     * @return mixed
     * @throws \App\Exceptions\ChangyiAuth\RequestOAServerException
     * @throws \Exception
     * @author zhuzhengqian@vchangyi.com
     */
    public static function checkMobile($mobile)
    {
        $requestArr = [];
        $mobile && $requestArr['mobile'] = $mobile;
        $authKey = Util::getAuthKey();

        $time = time();
        $sig  = Util::generateSig($requestArr , $time , $authKey);

        try {
            $request = \Requests::request(self::API_CHECK_MOBILE , [] , array_merge($requestArr , [
                'ts'  => $time ,
                'sig' => $sig
            ]));
        } catch (\Exception $e) {
            throw new RequestOAServerException;
        }

        $body = json_decode($request->body);
        if ($body->errcode != 0) {
            throw new MobileExistException($body->errmsg , $body->errcode);
        }

        return false;
    }

    /**
     * @param $corpId
     * @return mixed
     * @throws \App\Exceptions\ChangyiAuth\RequestOAServerException
     * @throws \Exception
     * @author zhuzhengqian@vchangyi.com
     */
    public static function checkCorpId($corpId)
    {
        $requestArr = [];
        $corpId && $requestArr['corpid'] = $corpId;
        $authKey = Util::getAuthKey();

        $time = time();
        $sig  = Util::generateSig([] , $time , $authKey);

        try {
            $request = \Requests::request(self::API_CHECK_CORP , [] , array_merge($requestArr , [
                'ts'  => $time ,
                'sig' => $sig
            ]));
        } catch (\Exception $e) {
            throw new RequestOAServerException;
        }

        $body = json_decode($request->body);
        if ($body->errcode != 0) {
            throw new \Exception($body->errmsg , $body->errcode);
        }

        return $body->result;
    }


    /**
     * 验证企业名称是否存在
     * @param $epName
     * @return mixed
     * @throws \App\Exceptions\ChangyiAuth\RequestOAServerException
     * @throws \Exception
     * @author zhuzhengqian@vchangyi.com
     */
    public static function checkEnterpriseName($epName)
    {
        $requestArr = [];
        $epName && $requestArr['epname'] = $epName;
        $authKey = Util::getAuthKey();

        $time = time();
        $sig  = Util::generateSig($requestArr , $time , $authKey);

        try {
            $request = \Requests::request(self::API_CHECK_EP_NAME , [] , array_merge($requestArr , [
                'ts'  => $time ,
                'sig' => $sig
            ]));
        } catch (\Exception $e) {
            throw new RequestOAServerException;
        }

        $body = json_decode($request->body);
        if ($body->errcode != 0) {
            throw new CompanyExistException($body->errmsg , $body->errcode);
        }

        return false;
    }


    /**
     * 检查epKey 是否存在
     * @param $epKey
     * @return bool
     * @throws \App\Exceptions\ChangyiAuth\EpKeyExistException
     * @throws \App\Exceptions\ChangyiAuth\RequestOAServerException
     * @author zhuzhengqian@vchangyi.com
     */
    public static function checkEpKey($epKey)
    {
        $requestArr = [];
        $epKey && $requestArr['enumber'] = $epKey;
        $authKey = Util::getAuthKey();

        $time = time();
        $sig  = Util::generateSig($requestArr , $time , $authKey);

        try {
            $request = \Requests::request(self::API_CHECK_EPKEY , [] , array_merge($requestArr , [
                'ts'  => $time ,
                'sig' => $sig
            ]));
        } catch (\Exception $e) {
            throw new RequestOAServerException;
        }

        $body = json_decode($request->body);

        if ($body->errcode != 0) {
            throw new EpKeyExistException($body->errmsg , $body->errcode);
        }

        return false;
    }


    /**
     * 新企业注册
     * @param \App\Modules\ChangyiAuth\EnterpriseAttributesInterface $enterpriseAttributes
     * @param null                                                   $companySize
     * @param null                                                   $ref
     * @param null                                                   $refDomain
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
     * @internal param $mobile
     * @internal param $realname
     * @internal param $email
     * @internal param $epName
     * @internal param $epKey
     * @internal param $password
     * @internal param null $industry
     * @author zhuzhengqian@vchangyi.com
     */
    public static function register(EnterpriseAttributesInterface $enterpriseAttributes , $companySize = null , $ref = null , $refDomain = null)
    {
        $requestArr = [];
        $requestArr['mobilephone'] = $enterpriseAttributes->getMobile();
        $requestArr['realname'] = $enterpriseAttributes->getRealName();
        $requestArr['email'] = $enterpriseAttributes->getEmail();
        $requestArr['ename'] = $enterpriseAttributes->getCompanyName();
        $requestArr['enumber'] = $enterpriseAttributes->getEpKey();
        $requestArr['password'] = $enterpriseAttributes->getPassword();
        $requestArr['industry'] = $enterpriseAttributes->getIndustry();
        $companySize && $requestArr['companysize'] = $companySize;
        $ref && $requestArr['ref'] = $ref;
        $refDomain && $requestArr['ref_domain'] = $refDomain;
        $authKey = Util::getAuthKey();

        $time = time();
        $sig  = Util::generateSig($requestArr , $time , $authKey);

        try {
            $request = \Requests::request(self::API_REGISTER , [] , array_merge($requestArr , [
                'ts'  => $time ,
                'sig' => $sig
            ]) , \Requests::POST);
        } catch (\Exception $e) {
            throw new RequestOAServerException;
        }

        $body = json_decode($request->body);

        switch($body->errcode){

            //手机号不合法
            case 4007:
                throw new MobileIllegalException($body->errmsg , $body->errcode);
                break;
            //手机号已经使用
            case 4008:
                throw new MobileExistException($body->errmsg , $body->errcode);
                break;
            //邮箱已经存在
            case 4013:
                throw new EmailExistException($body->errmsg , $body->errcode);
                break;
            //邮箱不合法
            case 4012:
                throw new EmailIllegalException($body->errmsg , $body->errcode);
                break;
            //真实姓名不合法
            case 4028:
                throw new RealNameIllegalException($body->errmsg , $body->errcode);
                break;
            //企业表示已经存在
            case 4018:
                throw new EpKeyExistException($body->errmsg , $body->errcode);
                break;
            //企业表示不合法
            case 4017:
                throw new EpKeyIllegalException($body->errmsg , $body->errcode);
                break;
            //公司名称不合法
            case 4030:
                throw new CompanyIllegalException($body->errmsg , $body->errcode);
                break;
        }

        if($body->errcode != 0){
            throw new \Exception($body->errmsg , $body->errcode);
        }

        return $body->result;
    }


}