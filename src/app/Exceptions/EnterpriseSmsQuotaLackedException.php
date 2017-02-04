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
 * 企业短信配额不足
 * Class EnterpriseSmsQuotaLackedException
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Exceptions
 */
class EnterpriseSmsQuotaLackedException extends BaseException
{
    protected $code = ApiMessageCode::CODE_SMS_QUOTA_LACKED;
    protected $message = '企业短信配额不足';
}