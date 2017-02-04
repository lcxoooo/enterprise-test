<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/2/20
 * Time: 22:34
 */

namespace App\MessageCodes;

/**
 * 通用消息码
 * Class MessageCode
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\MessageCodes\MessageCodes
 */
class MessageCode
{
    //通用的消息代码
    const CODE_FAILED                       = 200000;//失败
    const CODE_API_CLOSED                   = 200001;//API 接口维护中
    const CODE_NOT_FOUND_RESOURCE           = 200002;//请求的资源不存在（资源 404）
    const CODE_PARAM_ILLEGAL                = 200003;//参数不合法，必填的参数没有传入，或类型不合法
    const CODE_AUTH_ERROR                   = 200004;//用户身份鉴权不通过，请检查 Token 或 Cookie
}