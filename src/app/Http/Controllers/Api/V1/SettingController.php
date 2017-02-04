<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/11/1
 * Time: 9:54
 */

namespace App\Http\Controllers\Api\V1;

use App\Criteria\EnterpriseCriteria;
use App\Criteria\EnterpriseSetting\SettingKeyCriteria;
use App\Repositories\EnterpriseRepository;
use App\Repositories\EnterpriseSettingRepository;
use App\Transformers\EnterpriseSettingTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

/**
 * Class SettingController
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Http\Controllers\Api\V1
 */
class SettingController extends BaseController
{

    /**
     * @var EnterpriseSettingRepository
     */
    protected $enterpriseSettingRepository;

    /**
     * @var EnterpriseSettingTransformer
     */
    protected $enterpriseSettingTransformer;

    /**
     * SettingController constructor.
     * @param EnterpriseSettingRepository  $enterpriseSettingRepository
     * @param EnterpriseSettingTransformer $enterpriseSettingTransformer
     */
    public function __construct(EnterpriseSettingRepository $enterpriseSettingRepository,
                                EnterpriseSettingTransformer $enterpriseSettingTransformer)
    {
        $this->enterpriseSettingRepository = $enterpriseSettingRepository;
        $this->enterpriseSettingTransformer = $enterpriseSettingTransformer;
    }

    /**
     * @api {GET} /settings/:ep_key?key=:key 1.获取企业设置项
     * @apiDescription 获取企业设置项
     * @apiGroup settings
     * @apiParam  {String} key 设置项KEY
     * @apiSuccessExample {json} Success-Response:
        {
          "changyi_request_id": "581afabd5ed711.65337240",
          "inner_request_id": "581afabd5ed711.65337240",
          "success": true,
          "status_code": 200,
          "result_code": 0,
          "message": "请求成功",
          "response": {
            "id": 1,
            "ep_key": "suning",
            "key": "ee",
            "value": "gg",
            "description": null,
            "created_at": "2016-11-02 16:27:00",
            "updated_at": "2016-11-08 16:00:00"
          },
          "version": "v1",
          "servertime": 1478163133
        }
     * @param \Illuminate\Http\Request               $request
     * @param \App\Repositories\EnterpriseRepository $enterpriseRepository
     * @param                                        $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function getShow(Request $request, EnterpriseRepository $enterpriseRepository, $epKey)
    {
        $input = $request->only('key');

        $message = [
            'key.required' => '设置 Key 未传入',
        ];

        $validator = app('validator')->make(
            $input,
            [
                'key' => 'required|string',
            ],
            $message
        );

        if ($validator->fails()) {
            self::errorBadRequest($validator->messages()->first());
        }

        try {
            $enterprise = $enterpriseRepository->find($epKey);
        } catch (ModelNotFoundException $e) {
            self::errorNotFound("$epKey 企业不存在");
        }

        $setting = $this->enterpriseSettingRepository->pushCriteria(new EnterpriseCriteria($enterprise))
            ->pushCriteria(new SettingKeyCriteria($input['key']))
            ->first();

        if (!$setting) {
            self::errorNotFound("{$input['key']} 配置项不存在");
        }

        return $this->response->item($setting, $this->enterpriseSettingTransformer);
    }

    /**
     * @api {PUT} /settings/:ep_key 2.修改企业设置项
     * @apiDescription 修改企业设置项
     * @apiGroup settings
     * @apiParam  {String} key 企业设置项KEY
     * @apiParam  {String} value 企业设置项VALUE
     * @apiParam  {String} [description] 企业设置项描述
     * @apiSuccessExample {json} Success-Response:
        {
            "changyi_request_id": "581afbd010da20.93098567",
            "inner_request_id": "581afbd010da20.93098567",
            "success": true,
            "status_code": 200,
            "result_code": 0,
            "message": "请求成功",
            "response": {
                "id": 2,
                "ep_key": "suning",
                "key": "dd",
                "value": "token",
                "description": "的鹅鹅鹅",
                "created_at": "2016-11-03 16:55:29",
                "updated_at": "2016-11-03 16:56:48"
            },
            "version": "v1",
            "servertime": 1478163408
        }
     * @param \Illuminate\Http\Request               $request
     * @param \App\Repositories\EnterpriseRepository $enterpriseRepository
     * @param                                        $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function putModify(Request $request, EnterpriseRepository $enterpriseRepository, $epKey)
    {
        $input = $request->only(['key', 'value', 'description']);

        $message = [
            'key.required' => '设置 Key 未传入',
            'value.required' => '设置值未传入',
        ];

        $validator = app('validator')->make(
            $input,
            [
                'key' => 'required|string',
                'value' => 'required|string',
            ],
            $message
        );

        if ($validator->fails()) {
            self::errorBadRequest($validator->messages()->first());
        }

        try {
            $enterprise = $enterpriseRepository->find($epKey);
        } catch (ModelNotFoundException $e) {
            self::errorNotFound("$epKey 的企业不存在");
        }

        $setting = $this->enterpriseSettingRepository->pushCriteria(new EnterpriseCriteria($enterprise))
            ->pushCriteria(new SettingKeyCriteria($input['key']))
            ->first();

        if ($setting) {
            $setting = $this->enterpriseSettingRepository->update([
                'value' => $input['value'],
                'description' => $input['description'],
            ], $setting->id);
        } else {
            $setting = $this->enterpriseSettingRepository->create([
                'ep_key' => $epKey,
                'key' => $input['key'],
                'value' => $input['value'],
                'description' => $input['description'],
            ]);
        }

        return $this->response->item($setting, $this->enterpriseSettingTransformer);
    }
}