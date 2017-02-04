<?php
/**
 * Created by PhpStorm.
 * User: takat
 * Date: 2015/12/25
 * Time: 15:54
 */

namespace App\Libraries\SMS;

/**
 * Submail 短信发送接口
 * Class Submail
 * @author  takatost <wangjiajun@vchangyi.com>
 * @package App\Libraries\SMS
 */
class Submail
{

    /**
     * 发送短信接口
     */
    const API_SEND = 'https://api.submail.cn/message/xsend.json';

    protected $appId;
    protected $appSecret;

    public function __construct($appId = '', $appSecret = '')
    {
        $this->appId = $appId ? $appId : config('sms.submail.app_id');
        $this->appSecret = $appSecret ? $appSecret : config('sms.submail.app_secret');
    }

    /**
     * 发送短信
     * 注意，$text 字段必须包含短信签名
     * @param $mobile
     * @param $text
     */
    public function sendSms($mobile, $msgTplId, array $vars = [])
    {
        $msgTplId = urlencode("$msgTplId");
        $mobile = urlencode("$mobile");
        $postData = "appid=$this->appId&project=$msgTplId&to=$mobile&signature=$this->appSecret";

        if ($vars) {
            $postData .= '&vars=' . json_encode($vars);
        }

        $result = $this->sockPost(self::API_SEND, $postData);
        if (!$result) {
            throw new \Exception('发送短信失败，请重试，请求：' . $postData);
        }

        $result = json_decode($result, true);
        if (!isset($result['status']) || $result['status'] !== "success") {
            $msg = isset($result['msg']) ? $result['msg'] : '发送短信失败，请重试';
            $msgCode = isset($result['code']) ? $result['code'] : -1;
            throw new \Exception($msg . '，请求：' . $postData . '，返回：' . json_encode($result), $msgCode);
        }

        return $result;
    }

    private function sockPost($url, $query)
    {
        $data = "";
        $info = parse_url($url);
        $fp = fsockopen($info["host"], 80, $errno, $errstr, 30);
        if (!$fp) {
            return $data;
        }
        $head = "POST " . $info['path'] . " HTTP/1.0\r\n";
        $head .= "Host: " . $info['host'] . "\r\n";
        $head .= "Referer: http://" . $info['host'] . $info['path'] . "\r\n";
        $head .= "Content-type: application/x-www-form-urlencoded\r\n";
        $head .= "Content-Length: " . strlen(trim($query)) . "\r\n";
        $head .= "\r\n";
        $head .= trim($query);
        fputs($fp, $head);
        $header = "";
        while ($str = trim(fgets($fp, 4096))) {
            $header .= $str;
        }
        while (!feof($fp)) {
            $data .= fgets($fp, 4096);
        }

        return $data;
    }
}