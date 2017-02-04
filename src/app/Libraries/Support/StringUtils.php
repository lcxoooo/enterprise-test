<?php

namespace App\Libraries\Util;

/**
 * Class StringUtils
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Libraries\Util
 */
class StringUtils
{
    /**
     * 生成一个 $l 位的 62 进制随机字符串, 其第一位字符不为数字
     * @param $l
     * @return string
     * @author Haiming<haiming.wang@autotiming.com>
     */
    static public function mt_rand_base62($l)
    {
        $c = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($s = '', $cl = strlen($c) - 1, $i = 0; $i < $l; $s .= $c[mt_rand(0, $cl)], ++$i) ;
        $s[0] = is_numeric($s[0]) ? $letters[mt_rand(0, strlen($letters) - 1)] : $s[0];
        return $s;
    }


    /**
     * 生成8位ID
     * @return string
     * @author Haiming<haiming.wang@autotiming.com>
     */
    static public function generate8Id()
    {
        return self::mt_rand_base62(8);
    }

    /**
     * 屏蔽手机号中间4位
     * @param $mobile
     * @return mixed
     */
    public static function mosaicMobile($mobile) {
        return preg_replace('/(\d{3})\d{4}(\d{4})/i','$1****$2',$mobile);
    }

    static public function isMobileNumber($mobilNumber)
    {
        return preg_match("/1[34578]{1}\\d{9}$/", $mobilNumber);
    }

    static public function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * 日期与当前时间的间隔
     * @author Hanxiang<hanxiang.qiu@autotiming.com>
     */
    public static function timeTransfer($the_time)
    {
        $now_time = time();
        $show_time = strtotime($the_time);
        $dur = $now_time - $show_time;
        if ($dur < 0) {
            return $the_time;
        }

        if ($dur < 60) {
            return $dur . '秒前';
        }

        if ($dur < 3600) {
            return floor($dur / 60) . '分钟前';
        }

        if ($dur < 86400) {
            return floor($dur / 3600) . '小时前';
        }

        if ($dur < 259200) { //3天内
            return floor($dur / 86400) . '天前';
        } else {
            return $the_time;
        }
    }


    /**
     * 智能化的计算字符长度，一个中文算1个，一个英文算0.5个
     * @param $str
     * @author zhuzhengqian@vchangyi.com
     * @return int
     */
    public static function intellectStringLen($str)
    {

        $totalLeng = mb_strlen($str,'UTF-8');

        preg_match_all('/[0-9A-Za-z\- ,:\[\]\(\)\!\@\#\$\%\^\&\*]/',$str,$match);

        $asciillLength = count($match[0]);

        return intval(($totalLeng - $asciillLength) + ceil($asciillLength/2));
    }

    /**
     * 省略字符,用 $deli 代替
     * @param $str
     * @param $length
     * @param $deli
     * @author zhuzhengqian@vchangyi.com
     */
    public static function omissionString($str,$length,$deli)
    {
        $strLen = self::intellectStringLen($str);
        if($strLen > $length){
            $str = substr($str,0,$length) . $deli;
        }

        return $str;
    }


    /**
     * 判断电话，手机，400号码
     * @param  string $number 号码
     * @param  string $type 验证类型
     * @return bool
     * @author yangyang@vchangyi.com
     */
    public static function isTel($number,$type='')
    {
        $regExArr = array(
            'phone'  =>  '/^(\+?86-?)?(18|15|13|17)[0-9]{9}$/',
            'tel' =>  '/^(010|02\d{1}|0[3-9]\d{2})-\d{7,9}(-\d+)?$/',
            '400' =>  '/^400(-\d{3,4}){2}$/',
        );

        if($type && isset($regExArr[$type]))
        {
            return preg_match($regExArr[$type], $number) ? true:false;
        }
        foreach($regExArr as $regEx)
        {
            if(preg_match($regEx, $number ))
            {
                return true;
            }
        }
        return false;
    }

    /**
     * 下划线转成驼峰
     * @param $str
     * @return string
     * @author zhuzhengqian@vchangyi.com
     */
    public static function underscore2hump($str)
    {
        if(!$str){
            return $str;
        }

        $fragments = explode('_',$str);
        foreach($fragments as $k=>&$fragment){
            if(!$fragment){
                $fragment = '_'.$fragment;
            }else{
                $fragment = preg_match("/^_*$fragment/",$str) ? $fragment : ucfirst($fragment);
            }
        }
        return implode('',$fragments);
    }


    /**
     * 取得固定长度的随机数字
     * @param int $length
     * @author zhuzhengqian@vchangyi.com
     * @return string
     */
    public static function getRandomNumber($length=6)
    {
        $str = '';
        foreach(range(0,$length-1) as $v){
            $str .=rand(0,9);
        }

        return $str;
    }


    /**
     * 按照长度截取字符串,并且用 $delimiter 拼接
     * @param        $str
     * @param        $cultLength
     * @param string $delimiter
     * @return string
     * @author zhuzhengqian@vchangyi.com
     */
    public static function explodeStringAndFillWithDelimiter($str,$cultLength,$delimiter="...")
    {
        if(mb_strlen($str,'utf8') > $cultLength){
            return mb_substr($str,0,$cultLength,'utf8').$delimiter;
        }else{
            return $str;
        }
    }

    /**
     * 隐藏中间的字符窜
     * @author niuminghong <niuminghong@vchangyi.com>
     * @param
     * @return
     */
    public static function substr_cut($str){
        $strlen     = mb_strlen($str, 'utf8');
        $firstStr   = mb_substr($str, 0, 4, 'utf8');
        $lastStr     = mb_substr($str, -4, 4, 'utf8');
        return $strlen == 8 ? $firstStr . str_repeat('*', mb_strlen($str, 'utf-8') - 4) : $firstStr . str_repeat("*", $strlen - 8) . $lastStr;
    }

}