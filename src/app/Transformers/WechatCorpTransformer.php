<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\WechatCorp;

/**
 * Class WechatCorpTransformer
 * @package namespace App\Transformers;
 */
class WechatCorpTransformer extends TransformerAbstract
{

    protected $availableIncludes = [
        'corp_apps'
    ];

    protected $defaultIncludes = [
        'corp_apps'
    ];

    /**
     * Transform the \WechatCorp entity
     * @param \WechatCorp $model
     *
     * @return array
     */
    public function transform(WechatCorp $model)
    {
        return [
            'id'                    => (int)$model->id,
            'ep_key'                => $model->ep_key,
            'corp_id'               => $model->corp_id,
            'corp_secret'           => $model->corp_secret,
            'auth_info'             => $model->auth_info,
            'auth_fail_reason'      => $model->auth_fail_reason,
            'corp_name'             => $model->corp_name,
            'corp_round_logo_url'   => $model->corp_round_logo_url,
            'corp_square_logo_ur'   => $model->corp_square_logo_ur,
            'qrcode_url'            => $model->qrcode_url,
            'chat_secret'           => $model->chat_secret,
            'chat_token'            => $model->chat_token,
            'chat_encoding_aes_key' => $model->chat_encoding_aes_key,
            'status'                => $model->status,
            'created_at'            => $model->created_at->toDateTimeString(),
            'updated_at'            => $model->updated_at->toDateTimeString()
        ];
    }

    /**
     * @param \App\Models\WechatCorp $model
     * @return \League\Fractal\Resource\Collection
     * @author zhuzhengqian@vchangyi.com
     */
    public function includeCorpApps(WechatCorp $model)
    {
        return $this->collection($model->wechatCorpApps,new WechatCorpAppTransformer());
    }
}
