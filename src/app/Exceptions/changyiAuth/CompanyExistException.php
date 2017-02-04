<?php namespace App\Exceptions\ChangyiAuth;
/**
 * Created by Zhengqian.Zhu
 * Email: zhuzhengqian@vchangyi.com
 * Date: 16/4/28
 */
class CompanyExistException extends \Exception
{

    public function __construct($message , $code , \Exception $previous=null)
    {
        parent::__construct($message , $code , $previous);
    }
}