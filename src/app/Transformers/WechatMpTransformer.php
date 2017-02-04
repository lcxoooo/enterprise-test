<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\WechatMp;

/**
 * Class WechatMpTransformer
 * @package namespace App\Transformers;
 */
class WechatMpTransformer extends TransformerAbstract
{

    /**
     * Transform the \WechatMp entity
     * @param \WechatMp $model
     *
     * @return array
     */
    public function transform(WechatMp $model)
    {
        return [
            'id'                      => (int)$model->id,
            'ep_key'                  => $model->ep_key,
            'app_id'                  => $model->app_id,
            'app_secret'              => $model->app_secret,
            'server_token'            => $model->server_token,
            'encoding_aes_key'        => $model->encoding_aes_key,
            'component_refresh_token' => $model->component_refresh_token,
            'authorize_type'          => $model->authorize_type,
            'nick_name'               => $model->nick_name,
            'head_img'                => $model->head_img,
            'original_id'             => $model->original_id,
            'alias'                   => $model->alias,
            'qrcode_url'              => $model->qrcode_url,
            'status'                  => $model->status,
            'is_edit_menu_item'       => (bool)$model->is_edit_menu_item,
            'created_at'              => $model->created_at->toDateTimeString(),
            'updated_at'              => $model->updated_at->toDateTimeString()
        ];
    }
}
