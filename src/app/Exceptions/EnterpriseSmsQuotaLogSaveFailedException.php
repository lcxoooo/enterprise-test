<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/11/3
 * Time: 16:06
 */

namespace app\Exceptions;


use App\MessageCodes\ApiMessageCode;

/**
 * 保存短信配额增减记录失败
 * Class EnterpriseSmsQuotaLogSaveFailedException
 * @author  JohnWang <takato@vip.qq.com>
 * @package app\Exceptions
 */
class EnterpriseSmsQuotaLogSaveFailedException extends BaseException
{
    protected $code = ApiMessageCode::CODE_SMS_QUOTA_LOG_SAVE_FAILED;
    protected $message = '保存短信配额增减记录失败';
}