<?php namespace App\Modules\ChangyiAuth;

use App\Exceptions\ChangyiAuth\CompanyExistException;
use App\Exceptions\ChangyiAuth\MobileExistException;
use App\Exceptions\ChangyiAuth\RequestEnterpriseDetailException;
use App\Exceptions\ChangyiAuth\RequestEnterpriseListException;
use App\Exceptions\ChangyiAuth\RequestOAServerException;

/**
 * Created by Zhengqian.Zhu
 * Email: zhuzhengqian@vchangyi.com
 * Date: 16/4/28
 */
class EnterpriseApp
{


   const API_NEW_EP_APP = 'http://uc.vchangyi.com/PubApi/Api/EnterpriseApp/New';


    /**
     * 新增企业应用
     * @param      $epId
     * @param      $pluginId
     * @param null $name
     * @param null $extends
     * @return mixed
     * @throws \App\Exceptions\ChangyiAuth\RequestOAServerException
     * @throws \Exception
     * @author zhuzhengqian@vchangyi.com
     */
    public static function newEnterpriseApp($epId,$pluginId,$name=null,$extends=null)
    {
        $requestArr = [];
        $epId && $requestArr['ep_id'] = $epId;
        $pluginId && $requestArr['pluginid'] = $pluginId;
        $name && $requestArr['name'] = $name;
        $extends && $requestArr['extends'] = $extends;

        $authKey = Util::getAuthKey();

        $time = time();
        $sig  = Util::generateSig($requestArr , $time , $authKey);

        try {
            $request = \Requests::request(self::API_NEW_EP_APP , [] , array_merge($requestArr , [
                'ts'  => $time ,
                'sig' => $sig
            ]) , \Requests::POST);
        } catch (\Exception $e) {
            throw new RequestOAServerException;
        }

        $body = json_decode($request->body);

        if ($body->errcode != 0) {
            throw new \Exception($body->errmsg , $body->errcode);
        }

        return $body->result;
    }

}