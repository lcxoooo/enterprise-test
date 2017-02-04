<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/2/20
 * Time: 22:32
 */

namespace App\MessageCodes;

/**
 * API 消息码
 * Class ApiMessageCode
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\MessageCodes\MessageCodes
 */
class ApiMessageCode
{

    ## 企业基础信息 201xx
    const CODE_ENTERPRISE_NOT_EXIST = 20101;//企业不存在


    ## 企业注册/通行证登录 202xx
    const CODE_REG_REAL_NAME_ILLEGAL        = 202001;//真是姓名非法
    const CODE_REG_EP_KEY_ILLEGAL           = 202002;//企业表示不合法
    const CODE_REG_EP_KEY_FORBID            = 202003;//epkey 不合法,是保留字
    const CODE_REG_MOBILE_ILLEGAL           = 202004;//手机号非法
    const CODE_REG_MOBILE_EXIST             = 202005;//手机号已经存在
    const CODE_REG_EMAIL_ILLEGAL            = 202006;//邮箱非法
    const CODE_REG_EMAIL_EXIST              = 202007;//邮箱已经存在
    const CODE_REG_EP_KEY_EXIST             = 202008;//epKey 已经存在
    const CODE_COMPANY_NAME_ILLEGAL         = 202009;//公司名称非法
    const CODE_CHANGYI_LOGIN_PASSWORD_ERROR = 202010;//手机号或者密码错误
    const CODE_REG_PASSWORD_ILLEGAL         = 202011;//密码不合法


    /**
     * 企业号 203xx
     *
     */
    const CODE_WECHAT_CORP_EXIST     = 203001;//企业号已经存在
    const CODE_WECHAT_CORP_NOT_EXIST = 203002;//企业号已经存在
    const CODE_WECHAT_APP_EXIST      = 203003;//企业号应用已经存在

    ## 服务号 204xx

    const CODE_WECHAT_MP_EXIST     = 204001;//服务号已经存在
    const CODE_WECHAT_MP_NOT_EXIST = 204002;//服务号不存在


    ## 短信配额 205xx
    const CODE_SMS_QUOTA_LACKED          = 205001;//短信配额不足
    const CODE_SMS_QUOTA_DECR_FAILED     = 205002;//短信配额扣减失败
    const CODE_SMS_SEND_FAILED           = 205003;//企业短信发送失败
    const CODE_SMS_QUOTA_CHARGE_FAILED   = 205004;//企业短信配额充值失败
    const CODE_SMS_RECORD_SAVE_FAILED    = 205005;//保存短信发送记录失败
    const CODE_SMS_QUOTA_LOG_SAVE_FAILED = 205005;//保存短信配额增减记录失败
}