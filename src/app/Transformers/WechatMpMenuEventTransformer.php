<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\WechatMpMenuEvent;

/**
 * Class WechatMpMenuEventTransformer
 * @package namespace App\Transformers;
 */
class WechatMpMenuEventTransformer extends TransformerAbstract
{

    /**
     * Transform the \WechatMpMenuEvent entity
     * @param \WechatMpMenuEvent $model
     *
     * @return array
     */
    public function transform(WechatMpMenuEvent $model)
    {
        return [
            'id'            => (int)$model->id,
            'ep_key'        => $model->ep_key,
            'event'         => $model->event,
            'event_key'     => $model->event_key,
            'response'      => $model->response,
            'response_type' => $model->response_type,
            'created_at'    => $model->created_at->toDateTimeString(),
            'updated_at'    => $model->updated_at->toDateTimeString()
        ];
    }
}
