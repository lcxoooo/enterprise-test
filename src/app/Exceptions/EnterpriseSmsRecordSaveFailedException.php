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
 * 保存短信发送记录失败
 * Class EnterpriseSmsRecordSaveFailedException
 * @author  JohnWang <takato@vip.qq.com>
 * @package app\Exceptions
 */
class EnterpriseSmsRecordSaveFailedException extends BaseException
{
    protected $code = ApiMessageCode::CODE_SMS_RECORD_SAVE_FAILED;
    protected $message = '保存短信发送记录失败';
}