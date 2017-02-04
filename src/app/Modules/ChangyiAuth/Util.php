<?php namespace App\Modules\ChangyiAuth;
/**
 * Created by Zhengqian.Zhu
 * Email: zhuzhengqian@vchangyi.com
 * Date: 16/4/28
 */

class Util{

    /**
     * 生成请求畅移需要的sig
     * @param        $source
     * @param int    $timestamp
     * @param string $authKey
     * @return string
     * @internal param string $authkey
     * @author zhuzhengqian@vchangyi.com
     */
    public static function generateSig($source, $timestamp = 0, $authKey = '') {
        // 强制转换成数组
        $source = (array)$source;
        if (!empty($source['ts']) && 0 >= $timestamp) {
            $timestamp = $source['ts'];
        }
        unset($source['ts'], $source['sig']);
        // 参数数组
        $source[] = $timestamp;
        $source[] = $authKey;
        // 排序
        sort($source, SORT_STRING);
        return sha1(implode($source));
    }


    /**
     * 获取企业auth_key
     * @return mixed
     * @author zhuzhengqian@vchangyi.com
     */
    public static function getAuthKey()
    {
        return env('CHANGYI_AUTH_KEY','');
    }


}