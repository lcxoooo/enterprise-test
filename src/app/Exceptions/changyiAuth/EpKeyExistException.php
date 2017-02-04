<?php namespace App\Exceptions\ChangyiAuth;
/**
 * Created by Zhengqian.Zhu
 * Email: zhuzhengqian@vchangyi.com
 * Date: 16/4/28
 */
class EpKeyExistException extends \Exception
{

    public function __construct($message="请求OA失败" , $code=-1 , \Exception $previous=null)
    {
        parent::__construct($message , $code , $previous);
    }
}