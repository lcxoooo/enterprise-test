<?php

namespace App\Http\Controllers\Api\V1;

use App\Criteria\EpKeysCriteria;
use App\Entities\EnterpriseRegisterAttribute;
use App\Events\EnterpriseRegister;
use App\Events\EnterpriseWasDisable;
use App\Events\EnterpriseWasEnable;
use App\Events\EnterpriseWasModified;
use App\Exceptions\ChangyiAuth\CompanyIllegalException;
use App\Exceptions\ChangyiAuth\EmailExistException;
use App\Exceptions\ChangyiAuth\EmailIllegalException;
use App\Exceptions\ChangyiAuth\EpKeyExistException;
use App\Exceptions\ChangyiAuth\EpKeyIllegalException;
use App\Exceptions\ChangyiAuth\MobileExistException;
use App\Exceptions\ChangyiAuth\MobileIllegalException;
use App\Exceptions\ChangyiAuth\MobileOrPwdErrorException;
use App\Exceptions\ChangyiAuth\RealNameIllegalException;
use App\MessageCodes\ApiMessageCode;
use App\Models\Enterprise as ModelEnterprise;
use App\Models\Enterprise;
use App\Modules\ChangyiAuth\Admin;
use App\Repositories\EnterpriseRepository;
use App\Services\EnterpriseService;
use App\Transformers\EnterpriseTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Jhk\Staff\Role;
use Jhk\Staff\Staff;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class EnterpriseController
 * @author  JohnWang <takato@vip.qq.com>
 * @package App\Http\Controllers\Api\V1
 */
class EnterpriseController extends BaseController
{

    /**
     * @var EnterpriseRepository
     */
    protected $enterpriseRepository;

    protected $enterpriseService;

    /**
     *  企业注册默认过期时间（天数）
     */
    const ENTERPRISE_REGISTER_EXPIRE_DAYS = 15;

    const STAFF_ADMIN_ROLE = 'admin';

    public function __construct(EnterpriseRepository $enterpriseRepository, EnterpriseService $enterpriseService)
    {
        $this->enterpriseRepository = $enterpriseRepository;
        $this->enterpriseService = $enterpriseService;
    }

    /**
     * @api           {get} /enterprises?page=:page&pagesize=:pagesize 1.获取企业列表
     * @apiDescription 获取企业列表
     * @apiGroup       enterprises
     * @apiParam       {Number} [pagesize=20] 每页显示条目
     * @apiParam       {Number} [page=1] 当前页码
     * @apiVersion     1.0.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
        {
          "changyi_request_id": "5819b0ded06136.88569233",
          "inner_request_id": "5819b0ded06136.88569233",
          "success": true,
          "status_code": 200,
          "result_code": 0,
          "message": "请求成功",
          "response": {
            "data": [
              {
                "ep_key": "suning",
                "email": "hachi.zzq124@gmail.com",
                "mobile": "18662608682",
                "realname": "张三四",
                "company_name": "苏州畅移测试公司3",
                "logo_url": null,
                "status": "ABNORMAL",
                "register_from": "banner",
                "created_at": "2016-11-02 13:50:17",
                "updated_at": "2016-11-02 14:46:17"
              },
              {
                "ep_key": "suzhouchangyi4",
                "email": "hachi.zzq125@gmail.com",
                "mobile": "18662608683",
                "realname": "张三四",
                "company_name": "苏州畅移测试公司4",
                "logo_url": null,
                "status": "NORMAL",
                "register_from": "banner",
                "created_at": "2016-11-02 13:53:29",
                "updated_at": "2016-11-02 13:53:29"
              }
            ],
            "meta": {
              "pagination": {
                "total": 7,
                "count": 7,
                "per_page": 15,
                "current_page": 1,
                "total_pages": 1,
                "links": []
              }
            }
          },
          "version": "v1",
          "servertime": 1478078686
        }
     *
     * @apiSampleRequest http://ep.local.juhaoke.com/api/enterprises
     */
    public function index(EnterpriseTransformer $enterpriseTransformer,Request $request)
    {
        $pageSize = $request->has('pagesize') ? (int)$request->get('pagesize') : 20;

        $enterprises = $this->enterpriseRepository;
        if($request->has('ep_key')){
            $epKeyArr = explode(',',trim($request->get('ep_key'),','));
            $enterprises->pushCriteria(new EpKeysCriteria($epKeyArr));
        }
        $enterprises = $enterprises->paginate($pageSize);
        return $this->response->paginator($enterprises, $enterpriseTransformer);
    }

    /**
     * @api {POST} /enterprises/register 2.企业注册
     * @apiDescription 企业注册
     * @apiGroup enterprises
     * @apiParam  {String} mobile 手机号
     * @apiParam  {String} email 邮箱
     * @apiParam  {String} password 密码,6位字符以上，包括6位
     * @apiParam  {String} company_name 公司名称,大于两个字符，中文和英文都属于一个字符
     * @apiParam  {String} ep_key 企业标识，[a-z]{2}，大于等于两位
     * @apiParam  {String} realname 真实姓名，{2，5}，两位到五位，不包括边界
     * @apiSuccessExample {json} Success-Response:
    {
      "changyi_request_id": "587de13aa11d49.16750021",
      "inner_request_id": "587de13aa11d49.16750021",
      "success": true,
      "status_code": 200,
      "result_code": 0,
      "message": "请求成功",
      "response": {
        "ep_key": "suning999",
        "email": "zhuzhengqian@vchangyi.com",
        "mobile": "18662601713",
        "realname": "朱正钱",
        "company_name": "苏州测试公司",
        "logo_url": null,
        "store_on": false,
        "cashier_on": false,
        "seller_mode_on": false,
        "store_type": "PROBATION",
        "store_expires_at": "2017-02-01 17:17:46",
        "cashier_type": "PROBATION",
        "cashier_expires_at": "2017-02-01 17:17:46",
        "owned_max_store": 10,
        "owned_max_guide": 30,
        "level": "UNPROCESSED",
        "erp_on": 1,
        "status": "NORMAL",
        "register_from": "ERP",
        "created_at": "2017-01-17 17:17:46",
        "updated_at": "2017-01-17 17:17:46"
      },
      "version": "v1",
      "servertime": 1484644666
    }
     * @apiError 202001 真实姓名不合法
     * @apiError 202002 EPkEY不合法
     * @apiError 202003 EPkEY不合法
     * @apiError 202008 EPkEY已经存在
     * @apiError 202004 手机号不合法
     * @apiError 202005 手机号已经存在
     * @apiError 202007 邮箱已经存在
     * @apiError 202011 密码不合法
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function postRegister(Request $request)
    {
        $input = $request->only('mobile', 'email', 'password', 'company_name', 'ep_key', 'realname');
        $validator = Validator::make($input, [
            'mobile'       => 'required',
            'email'        => 'required|email',
            'company_name' => 'required',
            'ep_key'       => 'required|min:5|max:20',
            'realname'    => 'required',
            'password'     => 'required|min:6'
        ], [
            'mobile.required'       => "手机号必须设置",
            'email.required'        => '邮箱必须设置',
            'email.email'           => '邮箱格式不合法',
            'company_name.required' => '公司名称必须设置',
            'ep_key.required'       => '企业表示必须设置',
            'realname.required'    => '真实姓名必须设置',
            'password.min'          => '密码最短6位'
        ]);
        if ($validator->fails()) {
            self::errorBadRequest($validator->messages()->first());
        }

        //验证真实姓名
        if(mb_strlen($input['realname'],'utf8') < 2 && (mb_strlen($input['realname'],'utf8')> 5)) {
            self::errorBadRequest('真实姓名不符合规范', ApiMessageCode::CODE_REG_REAL_NAME_ILLEGAL);
        }

        //验证ep_key 格式
        if (!preg_match('/^[a-z][a-z0-9]{2,}$/', $input['ep_key'])) {
            self::errorBadRequest('企业标示不合法', ApiMessageCode::CODE_REG_EP_KEY_ILLEGAL);
        }

        if (in_array($input['ep_key'], [
            'test',
            'suning',
            'demo',
            'siss',
            'im',
            'www',
            'juhaoke',
            'vstore'
        ])) {
            self::errorBadRequest("{$input['ep_key']} 是保留字段,禁止使用", ApiMessageCode::CODE_REG_EP_KEY_FORBID);
        }

        //验证 ep_key 是否存在
        $exist = $this->enterpriseRepository->findWhere([
            'ep_key'=>$input['ep_key']
        ])->first();

        if($exist){
            self::errorBadRequest("ep_key {$input['ep_key']} 已经存在", ApiMessageCode::CODE_REG_EP_KEY_EXIST);
        }

        //验证手机号
        if(!preg_match('/^1[0-9]{10}$/',$input['mobile'])){
            self::errorBadRequest('手机号非法', ApiMessageCode::CODE_REG_MOBILE_ILLEGAL);
        }

        $exist = $this->enterpriseRepository->findWhere([
                    'mobile'=>$input['mobile']
                ])->first();

        if($exist){
            self::errorBadRequest('手机号已经存在', ApiMessageCode::CODE_REG_MOBILE_EXIST);
        }

        //验证邮箱
        $exist = $this->enterpriseRepository->findWhere([
                    'email'=>$input['email']
                ])->first();

        if($exist){
            self::errorBadRequest('邮箱已经存在', ApiMessageCode::CODE_REG_EMAIL_EXIST);
        }

        //验证公司名称
        if(!mb_strlen($input['company_name'],'utf8') > 2){
            self::errorBadRequest('公司名称非法', ApiMessageCode::CODE_COMPANY_NAME_ILLEGAL);
        }

        //验证密码
        if(!strlen($input['password']) >= 6){
            self::errorBadRequest('密码非法', ApiMessageCode::CODE_REG_PASSWORD_ILLEGAL);
        }

        //开始注册
        $enterprise = $this->enterpriseRepository->create([
            'ep_key'             => $input['ep_key'],
            'email'              => $input['email'],
            'mobile'             => $input['mobile'],
            'realname'           => $input['realname'],
            'company_name'       => $input['company_name'],
            'store_expires_at'   => Carbon::now()->addDays(self::ENTERPRISE_REGISTER_EXPIRE_DAYS)->toDateTimeString(),
            'cashier_expires_at' => Carbon::now()->addDays(self::ENTERPRISE_REGISTER_EXPIRE_DAYS)->toDateTimeString()
        ]);

        //再重新查找
        $enterprise = $this->enterpriseRepository->findWhere([
            'ep_key'=>$enterprise->ep_key
        ])->first();

/*        //获取所有角色列表
        try{
            $roleEntities = $roleSdk->index();
        }catch (\Exception $e){
            \Log::error($e);
        }

        foreach ($roleEntities as $roleEntity){
            if($roleEntity->label === self::STAFF_ADMIN_ROLE){
                $adminRoleId = $roleEntity->id;
            }
        }
        //创建超级管理员
        try {
            $staffSdk->create([
                'ep_key'    => $enterprise->ep_key,
                'worker_id' => 1001,
                'mobile'    => $input['mobile'],
                'email'     => $input['email'],
                'real_name' => $input['real_name'],
                'password'  => $input['password'],
                'roles'     => isset($adminRoleId) ? $adminRoleId : ''
            ]);
        } catch (\Exception $e) {
            \Log::error($e);
        }*/

        //触发企业注册事件
        try{
            event(new EnterpriseRegister($enterprise));
        }catch (\Exception $e){
            \Log::error("触发企业注册事件失败".$e->getMessage());
        }

        return $this->response->item($enterprise, new EnterpriseTransformer());
    }



    /**
     * @api            {get} /enterprises/:ep_key 4.获取企业详情
     * @apiDescription 获取企业详情
     * @apiGroup       enterprises
     * @apiVersion     1.0.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *       {
                changyi_request_id: "5819adfb349a82.04176344",
                inner_request_id: "5819adfb349a82.04176344",
                success: true,
                status_code: 200,
                result_code: 0,
                message: "请求成功",
                response: {
                    ep_key: "suning",
                    email: "hachi.zzq124@gmail.com",
                    mobile: "18662608682",
                    realname: "张三四",
                    company_name: "苏州畅移测试公司3",
                    logo_url: null,
                    status: "ABNORMAL",
                    register_from: "banner",
                    created_at: "2016-11-02 13:50:17",
                    updated_at: "2016-11-02 14:46:17"
                },
                version: "v1",
                servertime: 1478077947
            }
     *
     * @apiSampleRequest http://ep.local.juhaoke.com/api/enterprises/suning
     *
     * @param EnterpriseTransformer $enterpriseTransformer
     * @param                       $epKey
     * @return \Dingo\Api\Http\Response
     * @author         JohnWang <takato@vip.qq.com>
     */
    public function getShow(EnterpriseTransformer $enterpriseTransformer, $epKey)
    {
        try {
            $enterprise = $this->enterpriseRepository->find($epKey);
        } catch (ModelNotFoundException $e) {
            self::errorNotFound('企业不存在');
        }catch(\Exception $e){
            self::errorInternal($e->getMessage());
        }

        return $this->response->item($enterprise, $enterpriseTransformer);
    }

    /**
     * @api            {patch} /enterprises/:ep_key 5.修改企业信息
     * @apiDescription 修改企业信息
     * @apiGroup       enterprises
     * @apiParam       {String} [mobile] 企业管理员手机
     * @apiParam       {String} [realname] 企业管理员真实姓名
     * @apiParam       {String} [company_name] 企业名称
     * @apiParam       {String} [logo_url] 企业logo
     * @apiParam       {Bool} [store_on] 是否开启微店
     * @apiParam       {Bool} [cashier_on] 是否开启收银台
     * @apiParam       {Bool} [seller_mode_on] 是否开启导购模式
     * @apiParam       {String} [store_type] 微店类型，PROBATION,PAY,PERMANENT
     * @apiParam       {String} [cashier_type] 收银台类型，PROBATION,PAY,PERMANENT
     * @apiParam       {DateTime} [store_expires_at] 微店过期时间
     * @apiParam       {DateTime} [cashier_expires_at] 收银台过期时间
     * @apiParam       {Integer} [owned_max_store] 最大门店数
     * @apiParam       {Integer} [owned_max_guide]  最大导购数
     * @apiParam       {Integer} [level] 企业登记
     * @apiParam       {Bool} [erp_on]，是否开启ERP
     * @apiParam       {String} [status] 状态，NORMAL,DISABLE
     * @apiVersion     1.0.0
     * @apiSuccessExample {json} Success-Response:
    {
      "changyi_request_id": "587de13aa11d49.16750021",
      "inner_request_id": "587de13aa11d49.16750021",
      "success": true,
      "status_code": 200,
      "result_code": 0,
      "message": "请求成功",
      "response": {
        "ep_key": "suning999",
        "email": "zhuzhengqian@vchangyi.com",
        "mobile": "18662601713",
        "realname": "朱正钱",
        "company_name": "苏州测试公司",
        "logo_url": null,
        "store_on": false,
        "cashier_on": false,
        "seller_mode_on": false,
        "store_type": "PROBATION",
        "store_expires_at": "2017-02-01 17:17:46",
        "cashier_type": "PROBATION",
        "cashier_expires_at": "2017-02-01 17:17:46",
        "owned_max_store": 10,
        "owned_max_guide": 30,
        "level": "UNPROCESSED",
        "erp_on": 1,
        "status": "NORMAL",
        "register_from": "ERP",
        "created_at": "2017-01-17 17:17:46",
        "updated_at": "2017-01-17 17:17:46"
      },
      "version": "v1",
      "servertime": 1484644666
    }
     * @apiError 202001 真实姓名不合法
     * @apiError 202004 手机号不合法
     * @apiError 202005 手机号已经存在
     * @apiError 202007 邮箱已经存在
     * @param $epKey
     * @return \Dingo\Api\Http\Response
     * @author         JohnWang <takato@vip.qq.com>
     */
    public function patchModify(Request $request, EnterpriseTransformer $enterpriseTransformer, $epKey)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'store_on'           => 'nullable|boolean',
            'cashier_on'         => 'nullable|boolean',
            'seller_mode_on'     => 'nullable|boolean',
            'store_type'         => 'nullable|in:PROBATION,PAY,PERMANENT',
            'store_expires_at'   => 'nullable|date',
            'cashier_type'       => 'nullable|in:PROBATION,PAY,PERMANENT',
            'cashier_expires_at' => 'nullable|date',
            'owned_max_store'    => 'nullable|integer',
            'owned_max_guide'    => 'nullable|integer',
            'level'              => 'nullable|integer',
            'erp_on'             => 'nullable|boolean',
            'status'             => 'nullable|in:NORMAL,DISABLE'
        ]);

        if ($validator->fails()) {
            self::errorBadRequest($validator->messages()->first());
        }

        $enterprise = $this->enterpriseRepository->findWhere([
            'ep_key' => $epKey
        ])->first();

        if (!$enterprise) {
            self::errorNotFound('企业不存在');
        }

        //验证真实姓名
        if (isset($input['realname']) && mb_strlen($input['realname'], 'utf8') < 2 && (mb_strlen($input['realname'], 'utf8') > 5)) {
            self::errorBadRequest('真实姓名不符合规范', ApiMessageCode::CODE_REG_REAL_NAME_ILLEGAL);
        }

        //验证手机号
        if (isset($input['mobile'])) {
            if (!preg_match('/^1[0-9]{10}$/', $input['mobile'])) {
                self::errorBadRequest('手机号非法', ApiMessageCode::CODE_REG_MOBILE_ILLEGAL);
            }

            $exist = $this->enterpriseRepository->findWhere([
                'mobile' => $input['mobile'],
                'ep_key' => [
                    'ep_key',
                    '<>',
                    $enterprise->ep_key
                ]
            ])->first();

            if ($exist) {
                self::errorBadRequest('手机号已经存在', ApiMessageCode::CODE_REG_MOBILE_EXIST);
            }
        }

        if (isset($input['email'])) {
            //验证邮箱
            $exist = $this->enterpriseRepository->findWhere([
                'email'  => $input['email'],
                'ep_key' => [
                    'ep_key',
                    '<>',
                    $enterprise->ep_key
                ]
            ])->first();

            if ($exist) {
                self::errorBadRequest('邮箱已经存在', ApiMessageCode::CODE_REG_EMAIL_EXIST);
            }
        }

        if (isset($input['company_name'])) {
            //验证公司名称
            if (!mb_strlen($input['company_name'], 'utf8') > 2) {
                self::errorBadRequest('公司名称非法', ApiMessageCode::CODE_COMPANY_NAME_ILLEGAL);
            }
        }

        if(isset($input['store_type']) && $input['store_type'] === Enterprise::TYPE_PERMANENT){
            //永久的话
            $input['store_expires_at'] = null;
        }

        if(isset($input['cashier_type']) && $input['cashier_type'] === Enterprise::TYPE_PERMANENT){
            $input['cashier_expires_at'] = null;
        }

        try {
            $enterprise = $this->enterpriseRepository->update($input, $epKey);
        } catch (ValidatorException $e) {
            self::errorBadRequest($e->getMessageBag()->first());
        } catch (\Exception $e) {
            self::errorInternal('更新失败，' . $e->getMessage());
        }

        // 触发事件
        try{
            event(new EnterpriseWasModified($enterprise));
        }catch (\Exception $e){
            \Log::error('触发企业修改事件错误'.$e->getMessage());
        }

        return $this->response->item($enterprise, $enterpriseTransformer);
    }

    /**
     * @api            {patch} /enterprises/:ep_key/enable 6.启用企业
     * @apiDescription 启用企业
     * @apiGroup       enterprises
     * @apiVersion     1.0.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *       {
                changyi_request_id: "5819adfb349a82.04176344",
                inner_request_id: "5819adfb349a82.04176344",
                success: true,
                status_code: 200,
                result_code: 0,
                message: "请求成功",
                response: {
                    ep_key: "suning",
                    email: "hachi.zzq124@gmail.com",
                    mobile: "18662608682",
                    realname: "张三四",
                    company_name: "苏州畅移测试公司3",
                    logo_url: null,
                    status: "ABNORMAL",
                    register_from: "banner",
                    created_at: "2016-11-02 13:50:17",
                    updated_at: "2016-11-02 14:46:17"
                },
                version: "v1",
                servertime: 1478077947
            }
     * @apiError 200002 企业不存在
     * @apiSampleRequest http://ep.local.juhaoke.com/api/enterprises/suning/enable
     *
     * @param $epKey
     * @return \Dingo\Api\Http\Response
     * @author         JohnWang <takato@vip.qq.com>
     */
    public function patchEnable(EnterpriseTransformer $enterpriseTransformer, $epKey)
    {
        try {
            $enterprise = $this->enterpriseRepository->find($epKey);
        } catch (ModelNotFoundException $e) {
            self::errorNotFound('企业不存在');
        } catch (\Exception $e) {
            self::errorInternal('更新失败，'.$e->getMessage());
        }


        if ($enterprise->status !== Enterprise::ENTERPRISE_STATUS_NORMAL) {
            $enterprise = $this->enterpriseRepository->update(
                ['status' => Enterprise::ENTERPRISE_STATUS_NORMAL],
                $enterprise->ep_key
            );
            // 触发事件
            event(new EnterpriseWasEnable($enterprise));
        }

        return $this->response->item($enterprise, $enterpriseTransformer);


    }

    /**
     * @api            {patch} /enterprises/:ep_key/disable 7.禁用企业
     * @apiDescription 禁用企业
     * @apiGroup       enterprises
     * @apiVersion     1.0.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *       {
                changyi_request_id: "5819adfb349a82.04176344",
                inner_request_id: "5819adfb349a82.04176344",
                success: true,
                status_code: 200,
                result_code: 0,
                message: "请求成功",
                response: {
                    ep_key: "suning",
                    email: "hachi.zzq124@gmail.com",
                    mobile: "18662608682",
                    realname: "张三四",
                    company_name: "苏州畅移测试公司3",
                    logo_url: null,
                    status: "ABNORMAL",
                    register_from: "banner",
                    created_at: "2016-11-02 13:50:17",
                    updated_at: "2016-11-02 14:46:17"
                },
                version: "v1",
                servertime: 1478077947
            }
     * @apiError 200002 企业不存在
     * @apiSampleRequest http://ep.local.juhaoke.com/api/enterprises/suning/disable
     *
     * @param $epKey
     * @return \Dingo\Api\Http\Response
     * @author         JohnWang <takato@vip.qq.com>
     */
    public function patchDisable(EnterpriseTransformer $enterpriseTransformer, $epKey)
    {
        try {
            $enterprise = $this->enterpriseRepository->find($epKey);
        } catch (ModelNotFoundException $e) {
            self::errorNotFound('企业不存在');
        } catch (\Exception $e) {
            self::errorInternal('更新失败，'.$e->getMessage());
        }

        if ($enterprise->status !== Enterprise::ENTERPRISE_STATUS_DISABLE) {
            $enterprise = $this->enterpriseRepository->update(
                ['status' => Enterprise::ENTERPRISE_STATUS_DISABLE],
                $enterprise->ep_key
            );

            // 触发事件
            event(new EnterpriseWasDisable($enterprise));
        }

        return $this->response->item($enterprise, $enterpriseTransformer);
    }

    /**
     * @api {GET} /enterprises/check_mobile_exist/:mobile 检查手机有没有注册
     * @apiDescription 检查手机有没有注册
     * @apiGroup enterprises
     * @apiParam {String} mobile 手机号
     * @apiSuccessExample {json} Success-Response:
     * {
     * "request_id": "584654b4491634.85267144",
     * "msgcode": 100000,
     * "message": "操作成功",
     * ""data": {
     * "exist":false
     * },
     * "meta":null,
     * "version": "v1",
     * "servertime": 1481004212
     * }
     * @apiVersion 1.0.0
     * @param $mobile
     * @return mixed
     * @author: Zhengqian.zhu <zhuzhengqian@vchangyi.com>
     */
    public function checkEnterpriseExistByMobile($mobile)
    {
        $enterprise = $this->enterpriseRepository->findWhere([
            'mobile'=>$mobile,
            'status'=>Enterprise::ENTERPRISE_STATUS_NORMAL
        ])->first();

        return $this->response->array([
            'exist'=>!!$enterprise
        ]);
    }
}
