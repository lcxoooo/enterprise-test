<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\WechatMpTemplate;

/**
 * Class WechatMpTemplateTransformer
 * @package namespace App\Transformers;
 */
class WechatMpTemplateTransformer extends TransformerAbstract
{

    /**
     * Transform the \WechatMpTemplate entity
     * @param \WechatMpTemplate $model
     *
     * @return array
     */
    public function transform(WechatMpTemplate $model)
    {
        return [
            'id'         => (int)$model->id,
            'ep_key'     => $model->ep_key,
            'type'       => $model->type,
            'tpl_id'     => $model->tpl_id,
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString()
        ];
    }
}
