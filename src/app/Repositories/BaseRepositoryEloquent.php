<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/9/27
 * Time: 18:48
 */

namespace App\Repositories;

use Exception;
use App\Libraries\Validator\Contracts\ValidatorInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class BaseRepositoryEloquent
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Repositories
 */
abstract class BaseRepositoryEloquent extends BaseRepository
{
    /**
     * @var array
     */
    protected $messages = [
        'required' => ':attribute 必填',
        'in' => ':attribute 不在给定值域中',
        'numeric' => ':attribute 不是数字',
        'string' => ':attribute 不是字符串',
        'regex' => ':attribute 格式不正确'
    ];

    /**
     * Specify Validator class name of Prettus\Validator\Contracts\ValidatorInterface
     *
     * @return null
     * @throws Exception
     */
    public function validator()
    {

        if (isset($this->rules) && !is_null($this->rules) && is_array($this->rules) && !empty($this->rules)) {
            if (class_exists('App\Libraries\Validator\LaravelValidator')) {
                $validator = app('App\Libraries\Validator\LaravelValidator');
                if ($validator instanceof ValidatorInterface) {
                    $validator->setRules($this->rules)
                        ->setMessages($this->messages);

                    return $validator;
                }
            } else {
                throw new Exception(trans('repository::packages.prettus_laravel_validation_required'));
            }
        }

        return null;
    }

    /**
     * @param null $validator
     *
     * @return null|ValidatorInterface
     * @throws RepositoryException
     */
    public function makeValidator($validator = null)
    {
        $validator = !is_null($validator) ? $validator : $this->validator();

        if (!is_null($validator)) {
            $this->validator = is_string($validator) ? $this->app->make($validator) : $validator;

            if (!$this->validator instanceof ValidatorInterface) {
                throw new RepositoryException("Class {$validator} must be an instance of Prettus\\Validator\\Contracts\\ValidatorInterface");
            }

            return $this->validator;
        }

        return null;
    }

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @param string $column
     * @return float
     */
    public function avg($column = '*')
    {
        $this->applyCriteria();
        $this->applyScope();

        $result = $this->model->avg($column);

        $this->resetModel();

        return $result;
    }

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @param string $column
     * @return float
     */
    public function min($column = '*')
    {
        $this->applyCriteria();
        $this->applyScope();

        $result = $this->model->min($column);

        $this->resetModel();

        return $result;
    }

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @param string $column
     * @return float
     */
    public function max($column = '*')
    {
        $this->applyCriteria();
        $this->applyScope();

        $result = $this->model->max($column);

        $this->resetModel();

        return $result;
    }

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @param string $column
     * @return float
     */
    public function sum($column = '*')
    {
        $this->applyCriteria();
        $this->applyScope();

        $result = $this->model->sum($column);

        $this->resetModel();

        return $result;
    }

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @return int
     */
    public function count()
    {
        $this->applyCriteria();
        $this->applyScope();

        $result = $this->model->count();

        $this->resetModel();

        return $result;
    }

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @param       $column
     * @param int   $amount
     * @param array $extra
     * @return int
     */
    public function increment($column, $amount = 1, $extra = [])
    {
        $this->applyCriteria();
        $this->applyScope();

        $result = $this->model->increment($column, $amount, $extra);

        $this->resetModel();

        return $result;
    }

    /**
     * @author JohnWang <takato@vip.qq.com>
     * @param       $column
     * @param int   $amount
     * @param array $extra
     * @return int
     */
    public function decrement($column, $amount = 1, $extra = [])
    {
        $this->applyCriteria();
        $this->applyScope();

        $result = $this->model->decrement($column, $amount, $extra);

        $this->resetModel();

        return $result;
    }

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $attributes, array $values = [])
    {
        if (! is_null($instance = $this->model->where($attributes)->first())) {
            return $instance;
        }

        $instance = $this->model->newInstance($attributes + $values);

        $instance->save();

        return $instance;
    }

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        $instance = $this->model->firstOrNew($attributes);

        $instance->fill($values)->save();

        return $instance;
    }
}