<?php

namespace App\Listeners;

use App\Events\ExampleEvent;
use Dingo\Api\Dispatcher;
use Dingo\Api\Event\ResponseWasMorphed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddCustomResponse
{
    /**
     * @var Dispatcher
     */
    protected $api;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Dispatcher $api)
    {
        $this->api = $api;
    }

    /**
     * Handle the event.
     *
     * @param  ResponseWasMorphed $event
     * @return void
     */
    public function handle(ResponseWasMorphed $event)
    {
        $requestId = app('request')->headers->get('Changyi-Request-ID');
        $innerRequestId = uniqid('', true);
        $response = $event->content;
        $success = $event->response->isSuccessful();
        $code = !$success && isset($response['code']) ? $response['code'] : 0;
        $event->content = [
            'changyi_request_id' => $requestId ?: $innerRequestId,
            'inner_request_id'   => $innerRequestId,
            'success'            => $success,
            'status_code'        => $event->response->getStatusCode(),
            'result_code'        => $code,
            'message'            => !isset($response['message']) ? '请求成功' : $response['message'],
            'response'           => $success ? $response : null,
            'version'            => $this->api->getVersion(),
            'servertime'         => time()
        ];

        isset($response['errors']) && ($event->content['errors'] = $response['errors']);

        $event->response->withHeader('Changyi-Request-ID', $event->content['changyi_request_id'])
            ->withHeader('inner-request-id', $innerRequestId);

        $this->restLog(
            $event->content['changyi_request_id'],
            $event->content,
            $code,
            $event->content['message']
        );
    }

    /**
     * 保存请求日志
     * @author JohnWang <takato@vip.qq.com>
     * @param        $requestId
     * @param null   $data
     * @param int    $code
     * @param string $message
     */
    protected function restLog($requestId, $data = null, $code = 100000, $message = '请求成功')
    {
        app('log')->info('[Rest Log]', [
            'id'                => $requestId,
            'request'           => app('request')->except('file'),
            'request_route'     => app('api.router')->currentRouteName(),
            'response'          => $data,
            'msgcode'           => $code,
            'message'           => $message,
            'client_ip'         => app('request')->getClientIp(),
            'client_user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null,
        ]);
    }
}
