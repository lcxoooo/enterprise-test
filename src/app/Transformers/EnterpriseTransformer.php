<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Enterprise;

/**
 * Class EnterpriseTransformer
 * @package namespace App\Transformers;
 */
class EnterpriseTransformer extends TransformerAbstract
{

    /**
     * Transform the \Enterprise entity
     * @param Enterprise $model
     *
     * @return array
     */
    public function transform(Enterprise $model)
    {
        return [
            'ep_key'             => $model->ep_key,
            'email'              => $model->email,
            'mobile'             => $model->mobile,
            'realname'           => $model->realname,
            'company_name'       => $model->company_name,
            'logo_url'           => $model->logo_url,
            'store_on'           => boolval($model->store_on),
            'cashier_on'         => boolval($model->cashier_on),
            'seller_mode_on'     => boolval($model->seller_mode_on),
            'store_type'         => $model->store_type,
            'store_expires_at'   => $model->store_expires_at ? $model->store_expires_at->toDateTimeString() : null,
            'cashier_type'       => $model->cashier_type,
            'cashier_expires_at' => $model->cashier_expires_at ? $model->cashier_expires_at->toDateTimeString() : null,
            'owned_max_store'    => intval($model->owned_max_store),
            'owned_max_guide'    => intval($model->owned_max_guide),
            'level'              => $model->level,
            'erp_on'             => boolval($model->erp_on),
            'status'             => $model->status,
            'created_at'         => $model->created_at->toDateTimeString(),
            'updated_at'         => $model->updated_at->toDateTimeString()
        ];
    }
}
