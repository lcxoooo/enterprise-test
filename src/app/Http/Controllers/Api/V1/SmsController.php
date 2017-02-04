<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/10/31
 * Time: 15:24
 */

namespace App\Http\Controllers\Api\V1;

use App\Criteria\CreatedTimeCriteria;
use App\Criteria\EnterpriseCriteria;
use App\Exceptions\BaseException;
use App\MessageCodes\ApiMessageCode;
use App\Repositories\EnterpriseRepository;
use App\Repositories\EnterpriseSmsQuotaRepository;
use App\Repositories\EnterpriseSmsRecordRepository;
use App\Services\SmsService;
use App\Transformers\EnterpriseSmsQuotaTransformer;
use App\Transformers\EnterpriseSmsRecordTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SmsController extends BaseController
{
    /**
     * @var \App\Repositories\EnterpriseSmsRecordRepository
     */
    protected $enterpriseSmsRecordRepository;

    /**
     * @var \App\Repositories\EnterpriseRepository
     */
    protected $enterpriseRepository;

    /**
     * @var \App\Repositories\EnterpriseSmsQuotaRepository
     */
    protected $enterpriseSmsQuotaRepository;

    /**
     * @var \app\Services\SmsService
     */
    protected $smsService;

    /**
     * SmsController constructor.
     * @param \App\Repositories\EnterpriseSmsRecordRepository $enterpriseSmsRecordRepository
     * @param \App\Repositories\EnterpriseRepository          $enterpriseRepository
     * @param \App\Repositories\EnterpriseSmsQuotaRepository  $enterpriseSmsQuotaRepository
     * @param \app\Services\SmsService                        $smsService
     * @internal param \app\Services\SmsService $service
     */
    public function __construct(
        EnterpriseSmsRecordRepository $enterpriseSmsRecordRepository,
        EnterpriseRepository $enterpriseRepository,
        EnterpriseSmsQuotaRepository $enterpriseSmsQuotaRepository,
        SmsService $smsService
    ) {
        $this->enterpriseSmsRecordRepository = $enterpriseSmsRecordRepository;
        $this->enterpriseRepository          = $enterpriseRepository;
        $this->enterpriseSmsQuotaRepository  = $enterpriseSmsQuotaRepository;
        $this->smsService                    = $smsService;
    }

    /**
     * @api {POST} /sms/:ep_key/send 1.发送短信
     * @apiDescription 发送短信
     * @apiGroup sms
     * @apiParam  {String} mobile 接收人手机号
     * @apiParam  {String} template_id 短信模板ID
     * @apiParam  {String} arguments 短信模板参数json_encode后的json串
     * @apiSuccessExample {json} Success-Response:
        {
            "changyi_request_id": "581afbd010da20.93098567",
            "inner_request_id": "581afbd010da20.93098567",
            "success": true,
            "status_code": 200,
            "result_code": 0,
            "message": "请求成功",
            "response": null,
            "version": "v1",
            "servertime": 1478163408
        }
     * @apiError 205001 短信配额不足
     * @apiError 205003 短信发送失败
     * @param \Illuminate\Http\Request $request
     * @param                          $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function postSendEnterpriseSms(Request $request, $epKey)
    {
        $input     = $request->only('mobile', 'template_id', 'arguments');
        $validator = Validator::make($input, [
            'mobile'      => 'required|regex:/^1[0-9]{10}$/',
            'template_id' => 'required',
            'arguments'   => 'required'
        ], [
            'mobile.required'      => '缺少收件人手机号',
            'mobile.regex'         => '手机号不合法',
            'template_id.required' => '缺少模板ID',
            'arguments.required'   => '缺少模板参数',
        ]);

        if ($validator->fails()) {
            self::errorBadRequest($validator->messages()->first());
        }

        try {
            $enterprise = $this->enterpriseRepository->find($epKey);
        } catch (ModelNotFoundException $e) {
            self::errorNotFound("$epKey 企业不存在");
        } catch (\Exception $e) {
            self::errorInternal($e->getMessage());
        }

        try {
            $arguments = json_decode($input['arguments'], true);
        } catch (\Exception $e) {
            self::errorBadRequest("arguments 不是合法的JSON");
        }

        try {
            $this->smsService->send($input['mobile'], $input['template_id'], $enterprise, $arguments);
        } catch (BaseException $e) {
            self::errorBadRequest("短信发送失败".$e->getMessage(),ApiMessageCode::CODE_SMS_SEND_FAILED);
        }

        return $this->success();
    }

    /**
     * @api {POST} /sms/general_send 6.发送不需要EPKEY短信
     * @apiDescription 发送不需要 EPKEY 短信
     * @apiGroup sms
     * @apiParam  {String} mobile 接收人手机号
     * @apiParam  {String} template_id 短信模板ID
     * @apiParam  {String} arguments 短信模板参数json_encode后的json串
     * @apiSuccessExample {json} Success-Response:
        {
            "changyi_request_id": "581afbd010da20.93098567",
            "inner_request_id": "581afbd010da20.93098567",
            "success": true,
            "status_code": 200,
            "result_code": 0,
            "message": "请求成功",
            "response": null,
            "version": "v1",
            "servertime": 1478163408
        }
     * @apiError 205003 短信发送失败
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function postGeneralSend(Request $request)
    {
        $input = $request->only('mobile', 'template_id', 'arguments');
        $validator = Validator::make($input, [
            'mobile'      => 'required|regex:/^1[0-9]{10}$/',
            'template_id' => 'required',
            'arguments'   => 'required'
        ], [
            'mobile.required'      => '缺少收件人手机号',
            'mobile.regex'         => '手机号不合法',
            'template_id.required' => '缺少模板ID',
            'arguments.required'   => '缺少模板参数',
        ]);

        if ($validator->fails()) {
            self::errorBadRequest($validator->messages()->first());
        }

        try {
            $arguments = json_decode($input['arguments'], true);
        } catch (\Exception $e) {
            self::errorBadRequest("arguments 不是合法的JSON");
        }

        try {
            $this->smsService->generalSend($input['mobile'], $input['template_id'], $arguments);
        } catch (BaseException $e) {
            self::errorBadRequest("短信发送失败".$e->getMessage(),ApiMessageCode::CODE_SMS_SEND_FAILED);
        }

        return $this->success();
    }


    /**
     * @api {GET} /sms/:ep_key/records 2.企业发送记录
     * @apiDescription 企业发送记录
     * @apiGroup sms
     * @apiParam  {Number} [pagesize=20] 没有显示条数
     * @apiParam  {Number} [page=1] 当前页码
     * @apiParam {DataTime} [start_at] 开始时间
     * @apiParam {DataTime} [end_at] 结束时间
     * @apiSuccessExample {json} Success-Response:
        {
          "changyi_request_id": "581b031233abb5.41274151",
          "inner_request_id": "581b031233abb5.41274151",
          "success": true,
          "status_code": 200,
          "result_code": 0,
          "message": "请求成功",
          "response": {
            "data": [
              {
                "id": 1,
                "ep_key": "suning",
                "mobile": "18662601713",
                "content": "wefefe",
                "send_id": "www",
                "num": 1,
                "created_at": "2016-11-03 09:22:16",
                "updated_at": "2016-11-03 09:22:16"
              }
            ],
            "meta": {
              "pagination": {
                "total": 1,
                "count": 1,
                "per_page": 20,
                "current_page": 1,
                "total_pages": 1,
                "links": []
              }
            }
          },
          "version": "v1",
          "servertime": 1478165266
        }
     * @param \Illuminate\Http\Request $request
     * @param                          $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function getRecords(Request $request, $epKey)
    {
        $pagesize = $request->has('pagesize') ? $request->get('pagesize') : 20;
        try {
            $enterprise = $this->enterpriseRepository->find($epKey);
        } catch (ModelNotFoundException $e) {
            self::errorNotFound("$epKey 企业不存在");
        } catch (\Exception $e) {
            self::errorInternal($e->getMessage());
        }

        $smsRecord = $this->enterpriseSmsRecordRepository;
        if($request->has('start_at')){
            $smsRecord = $smsRecord->pushCriteria(new CreatedTimeCriteria($request->get('start_at'),'>='));
        }

        if($request->has('end_at')){
            $smsRecord = $smsRecord->pushCriteria(new CreatedTimeCriteria($request->get('end_at'),'<='));
        };

        $smsRecord = $smsRecord->pushCriteria(new EnterpriseCriteria($enterprise))
            ->orderBy('created_at','DESC')
            ->paginate($pagesize);

        return $this->response->paginator($smsRecord, new EnterpriseSmsRecordTransformer());
    }


    /**
     * @api {PATCH} /sms/:ep_key/charge 3.新增企业短信配合
     * @apiDescription 新增企业短信配合
     * @apiGroup sms
     * @apiParam  {Number} number 新增条数
     * @apiParam  {String} [comment] 备注
     * @apiSuccessExample {json} Success-Response:
        {
            "changyi_request_id": "581b03b2ee9a78.03454371",
            "inner_request_id": "581b03b2ee9a78.03454371",
            "success": true,
            "status_code": 200,
            "result_code": 0,
            "message": "请求成功",
            "response": {
                "id": 1,
                "ep_key": "suning",
                "sms_quota": 700,
                "used_sms_quota": 40,
                "total_sms_quota": 40,
                "created_at": "2016-11-03 06:25:24",
                "updated_at": "2016-11-03 17:30:26"
            },
            "version": "v1",
            "servertime": 1478165426
        }
     * @param \Illuminate\Http\Request $request
     * @param                          $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function patchCharge(Request $request, $epKey)
    {
        $number = $request->get('number', 0);
        if (!$number) {
            self::errorBadRequest("必须设置新增的条数");
        }

        $comment = $request->get('comment');

        try {
            $enterprise = $this->enterpriseRepository->find($epKey);
        } catch (ModelNotFoundException $e) {
            self::errorNotFound("$epKey 企业不存在");
        } catch (\Exception $e) {
            self::errorInternal($e->getMessage());
        }

        // 企业短信配额充值
        $enterpriseSmsQuota = $this->smsService->charge(
            $number,
            $enterprise,
            $comment
        );

        return $this->response->item($enterpriseSmsQuota, new EnterpriseSmsQuotaTransformer());
    }


    /**
     * @api {PATCH} /sms/:ep_key/decrease 4.减少企业短信配合
     * @apiDescription 减少企业短信配合
     * @apiGroup sms
     * @apiParam  {Number} number 减少条数
     * @apiParam  {String} [comment] 备注
     * @apiSuccessExample {json} Success-Response:
        {
            "changyi_request_id": "581b03b2ee9a78.03454371",
            "inner_request_id": "581b03b2ee9a78.03454371",
            "success": true,
            "status_code": 200,
            "result_code": 0,
            "message": "请求成功",
            "response": {
                "id": 1,
                "ep_key": "suning",
                "sms_quota": 700,
                "used_sms_quota": 40,
                "total_sms_quota": 40,
                "created_at": "2016-11-03 06:25:24",
                "updated_at": "2016-11-03 17:30:26"
            },
            "version": "v1",
            "servertime": 1478165426
        }
     * @param \Illuminate\Http\Request $request
     * @param                          $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function patchDecrease(Request $request, $epKey)
    {
        $number = $request->get('number', 0);
        if (!$number) {
            self::errorBadRequest("必须设置减去的条数");
        }

        $comment = $request->get('comment');
        try {
            $enterprise = $this->enterpriseRepository->find($epKey);
        } catch (ModelNotFoundException $e) {
            self::errorNotFound("$epKey 企业不存在");
        } catch (\Exception $e) {
            self::errorInternal($e->getMessage());
        }

        // 企业短信配额人工扣减
        $enterpriseSmsQuota = $this->smsService->decrease(
            $number,
            $enterprise,
            $comment
        );

        return $this->response->item($enterpriseSmsQuota, new EnterpriseSmsQuotaTransformer());
    }

    /**
     * @api {GET} /sms/:ep_key 5.企业短信配额详情
     * @apiDescription 企业短信配额详情
     * @apiGroup sms
     * @apiSuccessExample {json} Success-Response:
        {
            "changyi_request_id": "581b03b2ee9a78.03454371",
            "inner_request_id": "581b03b2ee9a78.03454371",
            "success": true,
            "status_code": 200,
            "result_code": 0,
            "message": "请求成功",
            "response": {
                "id": 1,
                "ep_key": "suning",
                "sms_quota": 700,
                "used_sms_quota": 40,
                "total_sms_quota": 40,
                "created_at": "2016-11-03 06:25:24",
                "updated_at": "2016-11-03 17:30:26"
            },
            "version": "v1",
            "servertime": 1478165426
        }
     * @param \Illuminate\Http\Request $request
     * @param                          $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function getShow(Request $request, $epKey)
    {

        try {
            $enterprise = $this->enterpriseRepository->find($epKey);
        } catch (ModelNotFoundException $e) {
            self::errorNotFound("$epKey 企业不存在");
        } catch (\Exception $e) {
            self::errorInternal($e->getMessage());
        }

        $enterpriseSmsQuota = $this->enterpriseSmsQuotaRepository
            ->pushCriteria(new EnterpriseCriteria($enterprise))
            ->first();

        if(!$enterpriseSmsQuota){
            self::errorNotFound();
        }

        return $this->response->item($enterpriseSmsQuota, new EnterpriseSmsQuotaTransformer());
    }

}