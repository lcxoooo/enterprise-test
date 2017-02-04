<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\EnterpriseSmsQuota;

/**
 * Class EnterpriseSmsQuotaTransformer
 * @package namespace App\Transformers;
 */
class EnterpriseSmsQuotaTransformer extends TransformerAbstract
{

    /**
     * Transform the \EnterpriseSmsQuota entity
     * @param \EnterpriseSmsQuota $model
     *
     * @return array
     */
    public function transform(EnterpriseSmsQuota $model)
    {
        return [
            'id'              => (int)$model->id,
            'ep_key'          => $model->ep_key,
            'sms_quota'       => (int)$model->sms_quota,
            'used_sms_quota'  => (int)$model->used_sms_quota,
            'total_sms_quota' => (int)$model->total_sms_quota,
            'created_at'      => $model->created_at->toDateTimeString(),
            'updated_at'      => $model->updated_at->toDateTimeString()
        ];
    }
}
