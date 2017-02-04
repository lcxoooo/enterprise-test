<?php
/**
 * Created by PhpStorm.
 * User: takat
 * Date: 2015/12/17
 * Time: 21:42
 */

namespace App\Exceptions;

/**
 * 基础异常类
 * Class BaseException
 * @package App\Exceptions
 */
class BaseException extends \Exception
{
    protected $response = null;

    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct();

        $message && $this->message = $message;
        $code && $this->code = $code;
    }

    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }
}