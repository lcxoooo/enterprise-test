<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\WechatCorpApp;

/**
 * Class WechatCorpAppTransformer
 * @package namespace App\Transformers;
 */
class WechatCorpAppTransformer extends TransformerAbstract
{

    /**
     * Transform the \WechatCorpApp entity
     * @param \WechatCorpApp $model
     *
     * @return array
     */
    public function transform(WechatCorpApp $model)
    {
        return [
            'id'               => (int)$model->id,
            'ep_key'           => $model->ep_key,
            'app_id'           => $model->app_id,
            'type'             => $model->type,
            'agent_id'         => $model->agent_id,
            'token'            => $model->token,
            'encoding_aes_key' => $model->encoding_aes_key,
            'permanent_code'   => $model->permanent_code,
            'authorize_type'   => $model->authorize_type,
            'description'      => $model->description,
            'status'           => $model->status,
            'created_at'       => $model->created_at->toDateTimeString(),
            'updated_at'       => $model->updated_at->toDateTimeString()
        ];
    }
}
