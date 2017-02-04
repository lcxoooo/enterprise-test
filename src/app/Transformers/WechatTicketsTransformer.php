<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Enterprise;

/**
 * Class EnterpriseTransformer
 * @package namespace App\Transformers;
 */
class WechatTicketsTransformer extends TransformerAbstract
{

    /**
     * Transform the \Enterprise entity
     * @param Enterprise $model
     *
     * @return array
     */
    public function transform($model)
    {
        return [
            'id'     => (int)$model->id ,
            'type'   => $model->type ,
            'ticket' => $model->ticket
        ];
    }
}
