<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\WechatTicket;

/**
 * Class WechatTicketTransformer
 * @package namespace App\Transformers;
 */
class WechatTicketTransformer extends TransformerAbstract
{

    /**
     * Transform the \WechatTicket entity
     * @param \WechatTicket $model
     *
     * @return array
     */
    public function transform(WechatTicket $model)
    {
        return [
            'id'         => (int)$model->id,
            'type'       => $model->type,
            'ticket'     => $model->ticket,
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString()
        ];
    }
}
