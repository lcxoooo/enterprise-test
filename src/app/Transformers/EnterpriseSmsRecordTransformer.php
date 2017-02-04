<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\EnterpriseSmsRecord;

/**
 * Class EnterpriseSmsRecordTransformer
 * @package namespace App\Transformers;
 */
class EnterpriseSmsRecordTransformer extends TransformerAbstract
{

    /**
     * Transform the \EnterpriseSmsRecord entity
     * @param \EnterpriseSmsRecord $model
     *
     * @return array
     */
    public function transform(EnterpriseSmsRecord $model)
    {
        return [
            'id'         => (int)$model->id,
            'ep_key'     => $model->ep_key,
            'mobile'     => $model->mobile,
            'content'    => $model->content,
            'send_id'    => $model->send_id,
            'num'        => (int)$model->num,
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString()
        ];
    }
}
