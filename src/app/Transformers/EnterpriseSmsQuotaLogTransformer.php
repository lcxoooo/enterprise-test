<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\EnterpriseSmsQuotaLog;

/**
 * Class EnterpriseSmsQuotaLogTransformer
 * @package namespace App\Transformers;
 */
class EnterpriseSmsQuotaLogTransformer extends TransformerAbstract
{

    /**
     * Transform the \EnterpriseSmsQuotaLog entity
     * @param EnterpriseSmsQuotaLog $model
     *
     * @return array
     */
    public function transform(EnterpriseSmsQuotaLog $model)
    {
        return [
            'id'         => (int)$model->id,
            'ep_key'     => $model->ep_key,
            'type'       => $model->type,
            'num'        => (int)$model->num,
            'record_id'  => (int)$model->record_id,
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString()
        ];
    }
}
