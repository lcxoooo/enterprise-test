<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/9/27
 * Time: 18:58
 */

namespace App\Repositories;

/**
 * Interface RepositoryAdvanceInterface
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Repositories
 */
interface RepositoryAdvanceInterface
{
    /**
     * @author JohnWang <takato@vip.qq.com>
     * @param string $column
     * @return float
     */
    public function avg($column = '*');

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @param string $column
     * @return float
     */
    public function min($column = '*');

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @param string $column
     * @return float
     */
    public function max($column = '*');

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @param string $column
     * @return float
     */
    public function sum($column = '*');

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @return int
     */
    public function count();

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @param       $column
     * @param int   $amount
     * @param array $extra
     * @return int
     */
    public function increment($column, $amount = 1, $extra = []);

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @param       $column
     * @param int   $amount
     * @param array $extra
     * @return int
     */
    public function decrement($column, $amount = 1, $extra = []);

}