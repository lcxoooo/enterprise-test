<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/11/3
 * Time: 14:18
 */

namespace App\Exceptions;

use App\MessageCodes\ApiMessageCode;

/**
 * 企业短信发送失败
 * Class EnterpriseSmsSendFailedException
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Exceptions
 */
class EnterpriseSmsSendFailedException extends BaseException
{
    protected $code = ApiMessageCode::CODE_SMS_SEND_FAILED;
    protected $message = '企业短信发送失败';
}