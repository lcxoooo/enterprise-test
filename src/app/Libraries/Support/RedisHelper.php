<?php
namespace App\Libraries\Support;

use Illuminate\Support\Facades\Redis;
use Predis\Connection\ConnectionException;

/**
 * Redis 工具集
 * Class RedisHelper
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Libraries\Support
 */
class RedisHelper
{

    /**
     * @var null|$this
     */
    protected static $redisInstance = null;

    /**
     * @var bool
     */
    protected static $isDisabled = false;

    /**
     * 禁止外部 new 保证单例
     */
    private function __construct() { }

    /**
     * 禁止外部 CLone 保证单例
     */
    private function __clone() { }

    /**
     * 取得 redis 实例
     * @return null
     */
    private static function getRedisInstance()
    {
        if (!self::$redisInstance) {
            self::$redisInstance = Redis::connection();
        }

        if (self::$isDisabled) {
            throw new ConnectionException(self::$redisInstance->getConnection());
        }

        return self::$redisInstance;
    }

    /**
     * 授权
     * @param $password
     * @return bool
     */
    public static function auth($password)
    {
        if ($password === '' || $password === null) {
            return true;
        }

        try {
            $redis = self::getRedisInstance();
            $redis->auth($password);

            return true;
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return false;
        }
    }

    /**
     * 设置 redis 值
     * @param      $key
     * @param      $value
     * @param null $expire
     * @return mixed
     */
    public static function setKey($key, $value, $expire = null)
    {
        try {
            $redis = self::getRedisInstance();
            if ($expire) {
                $redis->setex($key, $expire, $value);
            } else {
                $redis->set($key, $value);
            }

            return $value;
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return $value;
        }
    }

    /**
     * 仅保存一次
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function setNx($key, $value)
    {
        try {
            $redis = self::getRedisInstance();
            $redis->setnx($key, $value);

            return $value;
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return $value;
        }
    }


    /**
     * 取得redis 值
     * @param      $key
     * @param null $default
     * @return null
     */
    public static function getValue($key, $default = null)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->get($key) ? $redis->get($key) : $default;
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return $default;
        }
    }

    /**
     * 设置过期时间
     * @param $key
     * @param $expire
     * @return bool
     */
    public static function expire($key, $expire)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->expire(
                $key,
                $expire
            );
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return false;
        }
    }

    /**
     * Hash 批量获取值
     * @param       $cacheKey
     * @param array $keys
     * @return array
     */
    public static function hmGet($cacheKey, Array $keys)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->hmGet(
                $cacheKey,
                $keys
            );
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return [];
        }
    }

    /**
     * 取得hash 的值
     * @param $cacheKey
     * @param $key
     * @return array
     */
    public static function hGet($cacheKey, $key)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->hGet(
                $cacheKey,
                $key
            );
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return [];
        }
    }

    /**
     * Hash 批量设置值
     * @param       $cacheKey
     * @param array $keyVals
     * @return bool
     */
    public static function hmSet($cacheKey, Array $keyVals)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->hmSet(
                $cacheKey,
                $keyVals
            );
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return false;
        }
    }

    /**
     * set 增加值
     * @param $key
     * @param $subKey
     * @param $val
     * @return bool
     */
    public static function zAdd($key, $subKey, $val)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->zAdd(
                $key,
                $subKey,
                $val
            );
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return false;
        }
    }

    /**
     * Hash 设置值
     * @param $key
     * @param $subKey
     * @param $val
     * @return bool
     */
    public static function hSet($key, $subKey, $val)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->hSet(
                $key,
                $subKey,
                $val
            );
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return false;
        }
    }


    /**
     * @param $key
     * @return mixed
     */
    public static function deleteKey($key)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->del($key);
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return true;
        }
    }


    /**
     * @param array $keys
     * @return bool
     */
    public static function deleteKeys(array $keys)
    {
        try {
            foreach ($keys as $k) {
                self::deleteKey($k);
            }

            return count($keys);
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return 0;
        }
    }

    /**
     * incr,自增
     * @param     $key
     * @param int $by
     * @return mixed
     */
    public static function incr($key, $by = 1)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->incrby($key, $by);
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return 0;
        }
    }

    /**
     * Hash 自增
     * @param     $key
     * @param     $subKey
     * @param int $incr
     * @return int
     */
    public static function hIncrby($key, $subKey, $incr = 1)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->hIncrby($key, $subKey, $incr);
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return 0;
        }
    }

    /**
     * 检查 key 是否存在
     * @param $key
     * @return mixed
     */
    public static function checkKeyExist($key)
    {
        try {
            return self::getRedisInstance()->exists($key);
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return false;
        }
    }

    /**
     * 获取列表
     * @param $key
     * @param $start
     * @param $end
     * @return array
     */
    public static function lRange($key, $start, $end)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->lRange($key, $start, $end);
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return [];
        }
    }

    /**
     * 获取队列长度
     * @param $key
     * @return int
     */
    public static function llen($key)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->llen($key);
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return 0;
        }
    }

    /**
     * 进队
     * @param        $queue
     * @param        $value
     * @param string $type ，‘left’，‘right’
     * @return bool
     */
    public static function queuePush($queue, $value, $type = 'left')
    {
        try {
            $redis = self::getRedisInstance();
            if ($type == 'left') {
                return $redis->lpush($queue, $value);
            } elseif ($type == 'right') {
                return $redis->rpush($queue, $value);
            } else {
                return false;
            }
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return false;
        }
    }

    /**
     * 出队
     * @param        $queue
     * @param        $value
     * @param string $type ，‘left’，‘right’
     * @return bool
     */
    public static function queuePop($queue, $value, $type = 'left')
    {
        try {
            $redis = self::getRedisInstance();
            if ($type == 'left') {
                return $redis->lpop($queue, $value);
            } elseif ($type == 'right') {
                return $redis->rpop($queue, $value);
            } else {
                return false;
            }
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return false;
        }
    }

    /**
     * 获取/设置 排序 Set 值得分
     * @param      $key
     * @param      $val
     * @param null $score
     * @return bool
     * @author Wangjiajun
     */
    public static function zScore($key, $val, $score = null)
    {
        try {
            $redis = self::getRedisInstance();
            if ($score) {
                return $redis->zadd($key, $score, $val);
            } else {
                return $redis->zScore($key, $val);
            }
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return false;
        }
    }


    /**
     * 判断一个member 是否存在
     * @param $key
     * @param $member
     * @return array|bool
     *
     */
    public static function zExist($key, $member)
    {
        try {
            $redis = self::getRedisInstance();

            return !is_null($redis->zscore($key, $member));
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return [];
        }
    }


    /**
     * @param     $key
     * @param     $member
     * @param int $incr
     * @return array
     */
    public static function zIncr($key, $member, $incr = 1)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->zincrby($key, $incr, $member);
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return [];
        }
    }


    /**
     * 排序 Set 倒序获取列表
     * @param $key
     * @param $start
     * @param $end
     * @return array
     */
    public static function zRevRange($key, $start, $end)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->zRevRange($key, $start, $end);
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return [];
        }
    }

    /**
     * 排序 Set 获取列表
     * @param $key
     * @param $start
     * @param $end
     * @return array
     */
    public static function zRange($key, $start, $end)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->zRange($key, $start, $end);
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return [];
        }
    }


    /**
     * 排序方法
     * @param       $key
     * @param array $options
     * @return array
     */
    public static function sort($key, $options = [])
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->sort(
                $key,
                $options
            );
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return [];
        }
    }

    /**
     * 返回所有符合pattern 的redis KEY
     * @param $pattern
     * @return mixed
     */
    public static function keys($pattern)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->keys($pattern);
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return [];
        }
    }

    /**
     * 选择数据库
     * @param $db
     * @return mixed
     */
    public static function selectDb($db)
    {
        try {
            $redis = self::getRedisInstance();

            return $redis->select($db);
        } catch (ConnectionException $e) {
            self::$isDisabled = true;

            return true;
        }
    }

    /**
     * 拼接redis键名
     * @param string $name   键名
     * @param array  $params 参数
     * @return string 拼接后的键名
     */
    public static function makeKey($name, $params = [])
    {
        return $name . ($params ? (":" . implode(":", $params)) : '');
    }

}