<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class BaseModel
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Models
 */
class BaseModel extends Model implements Transformable
{
    use TransformableTrait;

    protected $guarded = ['id', 'updated_at'];

    protected $casts = [];

    protected $hidden = ['updated_at', 'deleted_at', 'extra'];

}
