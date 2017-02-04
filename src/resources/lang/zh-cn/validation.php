<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute 必须被接受',
    'active_url'           => ':attribute 必须是一个合法的 URL',
    'after'                => ':attribute 必须是 :date 之后的一个日期',
    'alpha'                => ':attribute 必须全部由字母字符构成。',
    'alpha_dash'           => ':attribute 必须全部由字母、数字、中划线或下划线字符构成',
    'alpha_num'            => ':attribute 必须全部由字母和数字构成',
    'array'                => ':attribute 必须是个数组',
    'before'               => ':attribute 必须是 :date 之前的一个日期',
    'between'              => [
        'numeric' => ':attribute 必须在 :min 到 :max 之间',
        'file'    => ':attribute 必须在 :min 到 :max KB之间',
        'string'  => ':attribute 必须在 :min 到 :max 个字符之间',
        'array'   => ':attribute 必须在 :min 到 :max 项之间',
    ],

    'boolean'              => ':attribute 类型必须是 true 或 false',
    'confirmed'            => ':attribute 二次确认密码不正确',
    'date'                 => ':attribute 必须是一个合法的日期',
    'date_format'          => ':attribute 与给定的格式 :format 不符合',
    'different'            => ':attribute 必须不同于:other',
    'digits'               => ':attribute 必须是 :digits 位',
    'digits_between'       => ':attribute 必须在 :min and :max 位之间',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => ':attribute 有重复值',
    'email'                => ':attribute 必须是一个合法的电子邮件地址',
    'filled'               => ':attribute 必须填写',
    'file'                 => ':attribute 必须是一个文件',
    'exists'               => '选定的 :attribute 是无效的',
    'image'                => ':attribute 必须是一个图片 (jpeg, png, bmp 或者 gif)',
    'in'                   => '选定的 :attribute 是无效的',
    'integer'              => ':attribute 必须是个整数',
    'ip'                   => ':attribute 必须是一个合法的 IP 地址。',
    'in_array'             => ':attribute 不在指定的集合 :other 中',
    'json'                 => ':attribute 必须是一个JSON字符串',
    'max'                  => [
        'numeric' => ':attribute 的最大长度为 :max 位',
        'file'    => ':attribute 的最大为 :max',
        'string'  => ':attribute 的最大长度为 :max 字符',
        'array'   => ':attribute 的最大个数为 :max 个',
    ],
    'mimes'                => ':attribute 的文件类型必须是:values',
    'mimetypes'            => ':attribute 的文件类型必须是:values',
    'min'                  => [
        'numeric' => ':attribute 的最小长度为 :min 位',
        'string'  => ':attribute 的最小长度为 :min 字符',
        'file'    => ':attribute 大小至少为:min KB',
        'array'   => ':attribute 至少有 :min 项',
    ],
    'not_in'               => '选定的 :attribute 是无效的',
    'numeric'              => ':attribute 必须是数字',
    'present'              => ':attribute 必须存在',

    'regex'                => ':attribute 格式是无效的',
    'required'             => ':attribute 必须填写',
    'required_if'          => ':attribute 是必须的当 :other 是 :value',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => ':attribute 必须填写,当 :values 存在时',
    'required_with_all'    => ':attribute 必须填写,当 :values 存在时',
    'required_without'     => ':attribute 必须填写,当 :values 是不存在的',
    'required_without_all' => ':attribute 必须填写,当 :values 不存在时',

    'string'               => ':attribute 必须是字符串',
    'unique'               => ':attribute 已经存在',
    'uploaded'             => ':attribute 上传失败',
    'same'                 => ':attribute 和 :other 必须匹配',
    'size'                 => [
        'numeric' => ':attribute 必须是 :size 位',
        'file'    => ':attribute 必须是 :size KB',
        'string'  => ':attribute 必须是 :size 个字符',
        'array'   => ':attribute 必须包括 :size 项',
    ],
    'url'                  => ':attribute 是无效的格式',
    'timezone'             => ':attribute 必须是个有效的时区',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'email' => [
            'required' => '邮箱不能为空',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
