<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/11/1
 * Time: 17:24
 */

namespace App\Criteria\EnterpriseSetting;


use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class SettingKeyCriteria
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Criteria\EnterpriseSetting
 */
class SettingKeyCriteria implements CriteriaInterface
{
    /**
     * @var string
     */
    protected $settingKey;

    /**
     * EnterpriseCriteria constructor.
     * @param string $settingKey
     */
    public function __construct($settingKey)
    {
        $this->settingKey = $settingKey;
    }

    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('key', $this->settingKey);
    }
}
