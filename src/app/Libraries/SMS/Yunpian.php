<?php
namespace App\Libraries\SMS;


/**
 * 云片短信通道接口
 * Class Yunpian
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Libraries\SMS
 */
class Yunpian
{

    /**
     * 普通短信接口
     */
    const API_SEND = 'http://yunpian.com/v1/sms/send.json';

    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * 发送短信
     * 注意，$text 字段必须包含短信签名
     * @author Luyu Zhang<goocarlos@gmail.com>
     * @param $mobile
     * @param $text
     */
    public function sendSms($mobile, $text)
    {
        $encodedText = urlencode("$text");
        $mobile = urlencode("$mobile");
        $postData = "apikey=$this->apiKey&text=$encodedText&mobile=$mobile";
        $result = $this->sockPost(self::API_SEND, $postData);
        if (!$result)
            throw new \Exception('发送短信失败，请重试');

        $result = json_decode($result, true);
        if (!isset($result['code']) || $result['code'] != 0) {
            $msg = isset($result['detail']) ? $result['detail'] : '发送短信失败，请重试';
            throw new \Exception($msg);
        }

        return $result['code'];
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
        $write = fputs($fp, $head);
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