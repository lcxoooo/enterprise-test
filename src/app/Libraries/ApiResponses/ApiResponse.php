<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/2/20
 * Time: 21:53
 */

namespace App\Libraries\ApiResponses;

use App\MessageCodes\MessageCode;
use Dingo\Api\Http\Response;
use League\Fractal\Resource\Collection;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * 接口应答组件
 * Class ApiResponse
 * @author JohnWang <takato@vip.qq.com>
 * @package App\Libraries\ApiResponses
 */
trait ApiResponse
{

    /**
     * 获取成功
     * @author JohnWang <takato@vip.qq.com>
     * @param null $data
     * @return Response
     */
    public static function success($data = null)
    {
        return self::result(200, $data);
    }

    /**
     * 资源成功创建
     * @author JohnWang <takato@vip.qq.com>
     * @param null $data
     * @return Response
     */
    public static function created($data = null)
    {
        return self::result(201, $data);
    }

    /**
     * 请求接收，将被异步处理
     * @author JohnWang <takato@vip.qq.com>
     * @param null $data
     * @return Response
     */
    public static function accepted($data = null)
    {
        return self::result(202, $data);
    }

    /**
     * 资源删除成功
     * @author JohnWang <takato@vip.qq.com>
     * @return Response
     */
    public static function noContent()
    {
        return self::result(204);
    }

    /**
     * 正确的返回统一格式
     * @author JohnWang <takato@vip.qq.com>
     * @param integer $statusCode
     * @param null    $data
     * @return Response
     */
    public static function result($statusCode = 200, $data = null)
    {
        if (!$data instanceof Response) {
            $messageResponse = new Response($data);
        } else {
            $messageResponse = $data;
        }

        return $messageResponse->statusCode($statusCode);
    }

    /**
     * 参数不合法 统一返回格式
     * 400
     *
     * @param string $message
     * @param int    $code
     * @author         JohnWang <takato@vip.qq.com>
     * @throws BadRequestHttpException
     */
    public static function errorBadRequest($message = "参数不合法", $code = MessageCode::CODE_PARAM_ILLEGAL)
    {
        throw new BadRequestHttpException($message, null, $code);
    }

    /**
     * 未授权的访问 统一返回格式
     * 401
     * @author JohnWang <takato@vip.qq.com>
     * @param null $message
     * @param      $code
     * @throws UnauthorizedHttpException
     */
    public static function errorUnauthorized($message = "未授权的访问", $code = MessageCode::CODE_AUTH_ERROR)
    {
        throw new UnauthorizedHttpException($message, null, $code);
    }

    /**
     * 禁止访问 统一返回格式
     * 403
     * @author JohnWang <takato@vip.qq.com>
     * @param null $message
     * @param      $code
     * @throws AccessDeniedHttpException
     */
    public static function errorForbidden($message = "禁止访问", $code = MessageCode::CODE_API_CLOSED)
    {
        throw new AccessDeniedHttpException($message, null, $code);
    }

    /**
     * 找不到资源 统一返回格式
     * 404
     * @author JohnWang <takato@vip.qq.com>
     * @param string $message
     * @param        $code
     * @throws NotFoundHttpException
     */
    public static function errorNotFound($message = "资源不存在", $code = MessageCode::CODE_NOT_FOUND_RESOURCE)
    {
        throw new NotFoundHttpException($message, null, $code);
    }

    /**
     * 内部错误 统一返回格式
     * 500
     * @author JohnWang <takato@vip.qq.com>
     * @param string $message
     * @param        $code
     * @throws HttpException
     */
    public static function errorInternal($message = '内部错误', $code = MessageCode::CODE_FAILED)
    {
        throw new HttpException(500, $message, null, [], $code);
    }
}