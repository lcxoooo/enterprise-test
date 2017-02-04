<?php namespace App\Modules\ChangyiAuth;
use App\Exceptions\ChangyiAuth\MobileOrPwdErrorException;
use App\Exceptions\ChangyiAuth\RequestAdminListException;
use App\Exceptions\ChangyiAuth\RequestOAServerException;

/**
 * Created by Zhengqian.Zhu
 * Email: zhuzhengqian@vchangyi.com
 * Date: 16/4/28
 */

class Admin
{

    const API_EP_ADMINS = 'http://uc.vchangyi.com/PubApi/Api/EnterpriseAdminer/List';

    const API_EP_ADMIN_DETAIL = "http://uc.vchangyi.com/PubApi/Api/EnterpriseAdminer/Fetch";

    const API_LOGIN = "http://uc.vchangyi.com/PubApi/Api/EnterpriseAdminer/LoginHaomai";

    const API_GET_INFO_BY_CODE = 'http://uc.vchangyi.com/PubApi/Api/EnterpriseAdminer/GetUserHaomai';

    /**
     * 企业管理员列表
     * @param      $epId
     * @param null $page
     * @param null $perpage
     * @return mixed
     * @throws \App\Exceptions\ChangyiAuth\RequestAdminListException
     * @throws \App\Exceptions\ChangyiAuth\RequestOAServerException
     * @author zhuzhengqian@vchangyi.com
     */
    public static function lists($epId,$page=null,$perpage=null)
    {
        $requestArr = [];
        $epId && $requestArr['ep_id'] = $epId;
        $page && $requestArr['page'] = $page;
        $perpage && $requestArr['perpage'] = $perpage;
        $authKey = Util::getAuthKey();

        $time = time();
        $sig = Util::generateSig($requestArr,$time,$authKey);

        try{
            $request = \Requests::request(self::API_EP_ADMINS,[],array_merge($requestArr,[
                'ts'=>$time,
                'sig'=>$sig
            ]));
        }catch (\Exception $e){
            throw new RequestOAServerException;
        }

        $body = json_decode($request->body);
        if($body->errcode != 0){
            throw new RequestAdminListException($body->errmsg,$body->errcode);
        }

        return $body->result->list;

    }


    /**
     * 管理员详情
     * @param $epId
     * @param $mobile
     * @return mixed
     * @throws \Exception
     * @author zhuzhengqian@vchangyi.com
     */
    public static function detail($epId,$mobile)
    {
        $requestArr = [];
        $epId && $requestArr['ep_id'] = $epId;
        $mobile && $requestArr['mobile'] = $mobile;
        $authKey = Util::getAuthKey();

        $time = time();
        $sig = Util::generateSig($requestArr,$time,$authKey);

        try{
            $request = \Requests::request(self::API_EP_ADMIN_DETAIL,[],array_merge($requestArr,[
                'ts'=>$time,
                'sig'=>$sig
            ]));
        }catch (\Exception $e){
            throw new RequestOAServerException;
        }

        $body = json_decode($request->body);
        if($body->errcode != 0){
            throw new \Exception($body->errmsg,$body->errcode);
        }

        return $body->result->adminer;
    }


    /**
     * 登陆取得 Code
     * @param $mobile
     * @param $password
     * @return mixed
     * @throws \Exception
     * @author zhuzhengqian@vchangyi.com
     */
    public static function login($mobile,$password)
    {
        $requestArr = [];
        $mobile && $requestArr['mobile'] = $mobile;
        $password && $requestArr['password'] = $password;

        $authKey = Util::getAuthKey();

        $time = time();
        $sig = Util::generateSig($requestArr,$time,$authKey);

        try{
            $request = \Requests::request(self::API_LOGIN,[],[
                'username'=>$mobile,
                'passwd'=>$password,
                'ts'=>$time,
                'sig'=>$sig
            ],\Requests::POST);
        }catch (\Exception $e){
            throw new RequestOAServerException;
        }

        $body = json_decode($request->body);

        if($body->errcode == 110006 || $body->errcode == 110005){
            throw new MobileOrPwdErrorException($body->errmsg,$body->errcode);
        }
        if($body->errcode != 0){
            throw new \Exception($body->errmsg,$body->errcode);
        }

        return $body->result;
    }


    /**
     * 通过 code 取得个人信息
     * @param $code
     * @return mixed
     * @throws \Exception
     * @author zhuzhengqian@vchangyi.com
     */
    public static function getUserInfoByCode($code)
    {
        $requestArr = [];
        $code && $requestArr['code'] = $code;

        $authKey = Util::getAuthKey();

        $time = time();
        $sig = Util::generateSig($requestArr,$time,$authKey);

        try{
            $request = \Requests::request(self::API_GET_INFO_BY_CODE,[],[
                'code'=>$code,
                'ts'=>$time,
                'sig'=>$sig
            ]);
        }catch (\Exception $e){
            throw new RequestOAServerException;
        }

        $body = json_decode($request->body);

        if($body->errcode != 0){
            throw new \Exception($body->errmsg,$body->errcode);
        }

        return $body->result;
    }
}