<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\EnterpriseSetting;

/**
 * Class EnterpriseSettingTransformer
 * @package namespace App\Transformers;
 */
class EnterpriseSettingTransformer extends TransformerAbstract
{

    /**
     * Transform the \EnterpriseSetting entity
     * @param EnterpriseSetting $model
     *
     * @return array
     */
    public function transform(EnterpriseSetting $model)
    {
        return [
            'id'          => (int)$model->id,
            'ep_key'      => $model->ep_key,
            'key'         => $model->key,
            'value'       => $model->value,
            'description' => $model->description,
            'created_at'  => $model->created_at->toDateTimeString(),
            'updated_at'  => $model->updated_at->toDateTimeString()
        ];
    }
}
