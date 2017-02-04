<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/10/31
 * Time: 15:24
 */

namespace App\Http\Controllers\Api\V1;


use App\MessageCodes\ApiMessageCode;
use App\Models\WechatCorp;
use App\Models\WechatCorpApp;
use App\Models\WechatTicket;
use App\Repositories\EnterpriseRepository;
use App\Repositories\WechatCorpAppRepository;
use App\Repositories\WechatCorpRepository;
use App\Repositories\WechatTicketRepository;
use App\Transformers\WechatCorpAppTransformer;
use App\Transformers\WechatCorpTransformer;
use App\Transformers\WechatTicketsTransformer;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Prettus\Validator\Exceptions\ValidatorException;

class WechatCorpController extends BaseController
{

    protected $wechatTicketRepositoryEloquent;
    protected $wechatCorpRepository;
    protected $wechatCorpAppRepository;
    protected $enterpriseRepository;

    public function __construct(WechatCorpRepository $wechatCorpRepository,
                                WechatTicketRepository $wechatTicketRepositoryEloquent,
                                WechatCorpAppRepository $wechatCorpAppRepository,
                                EnterpriseRepository $enterpriseRepository)
    {
        $this->wechatTicketRepositoryEloquent = $wechatTicketRepositoryEloquent;
        $this->wechatCorpRepository = $wechatCorpRepository;
        $this->wechatCorpAppRepository = $wechatCorpAppRepository;
        $this->enterpriseRepository = $enterpriseRepository;
    }

    /**
     * @api {GET} /wechat_corps 3.企业号列表
     * @apiDescription 企业号列表
     * @apiGroup corps
     * @apiParam  {Number} [pagesize=20] 没有显示条数
     * @apiParam  {Number} [page=1] 当前页码
     * @apiSuccessExample {json} Success-Response:
    {
      "changyi_request_id": "581ae85b230383.09399716",
      "inner_request_id": "581ae85b230383.09399716",
      "success": true,
      "status_code": 200,
      "result_code": 0,
      "message": "请求成功",
      "response": {
        "data": [
          {
            "id": 1,
            "ep_key": "suning",
            "corp_id": "corp_id",
            "corp_secret": "corp_sec",
            "auth_info": "auth_info",
            "auth_fail_reason": null,
            "corp_name": "suning",
            "corp_round_logo_url": "htttp2",
            "corp_square_logo_ur": null,
            "qrcode_url": "http://qrcode",
            "chat_secret": null,
            "chat_token": null,
            "chat_encoding_aes_key": null,
            "status": "NORMAL",
            "created_at": "2016-11-01 08:42:40",
            "updated_at": "2016-11-01 08:42:40",
            "corp_apps": {
              "data": [
                {
                  "id": 1,
                  "ep_key": "suning",
                  "app_id": 2,
                  "type": "chat",
                  "agent_id": "2",
                  "token": "token",
                  "encoding_aes_key": "key",
                  "permanent_code": null,
                  "authorize_type": null,
                  "description": "des",
                  "status": "NORMAL",
                  "created_at": "2016-11-01 08:44:50",
                  "updated_at": "2016-11-02 15:36:10"
                }
              ]
            }
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
      "servertime": 1478158427
    }
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function index(Request $request)
    {
        $pagesize = $request->has('pagesize') ? $request->get('pagesize') : 20;
        return $this->response->paginator($this->wechatCorpRepository->paginate($pagesize),new WechatCorpTransformer());
    }

    /**
     * @api {GET} /wechat_corps/ticket 1.获取企业号开放平台Tickets
     * @apiDescription 获取企业号开放平台 Tickets
     * @apiGroup corps
     * @apiSuccessExample {json} Success-Response:
        {
          "changyi_request_id": "581ae89d7c6402.41024946",
          "inner_request_id": "581ae89d7c6402.41024946",
          "success": true,
          "status_code": 200,
          "result_code": 0,
          "message": "请求成功",
          "response": {
            "id": 2,
            "type": "CORP",
            "ticket": "18662608681"
          },
          "version": "v1",
          "servertime": 1478158493
        }
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function getTicket()
    {
        $ticket = $this->wechatTicketRepositoryEloquent->findWhere([
            'type'=>WechatTicket::TYPE_CORP
                                                         ])
            ->first();


        if(!$ticket){
            self::errorNotFound('Ticket 不存在');
        }

        return $this->response->item($ticket,new WechatTicketsTransformer());
    }


    /**
     * @api {POST} /wechat_corps/ticket 2.设置企业号开放平台Tickets
     * @apiDescription 设置企业号开放平台Tickets
     * @apiGroup corps
     * @apiParam  {String} ticket ticket值
     * @apiSuccessExample {json} Success-Response:
        {
          "changyi_request_id": "581ae89d7c6402.41024946",
          "inner_request_id": "581ae89d7c6402.41024946",
          "success": true,
          "status_code": 200,
          "result_code": 0,
          "message": "请求成功",
          "response": {
            "id": 2,
            "type": "CORP",
            "ticket": "18662608681"
          },
          "version": "v1",
          "servertime": 1478158493
        }
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function postTicket(Request $request)
    {
        $ticket = $request->get('ticket');

        if(!$ticket){
            self::errorBadRequest('缺少 Ticket 值');
        }

        $ticket = $this->wechatTicketRepositoryEloquent->updateOrCreate(['type'=>WechatTicket::TYPE_CORP],[
            'type'=>WechatTicket::TYPE_CORP,
            'ticket'=>$ticket
        ]);

        return $this->response->item($ticket,new WechatTicketsTransformer());
    }


    /**
     * @api {PATCH} /wechat_corps/:ep_key/enable 4.设置企业号状态为可用
     * @apiDescription 设置企业号状态为可用
     * @apiGroup corps
     * @apiSuccessExample {json} Success-Response:
    {
      "changyi_request_id": "581aeaf3dd2135.57095351",
      "inner_request_id": "581aeaf3dd2135.57095351",
      "success": true,
      "status_code": 200,
      "result_code": 0,
      "message": "请求成功",
      "response": {
        "id": 1,
        "ep_key": "suning",
        "corp_id": "corp_id",
        "corp_secret": "corp_sec",
        "auth_info": "auth_info",
        "auth_fail_reason": null,
        "corp_name": "suning",
        "corp_round_logo_url": "htttp2",
        "corp_square_logo_ur": null,
        "qrcode_url": "http://qrcode",
        "chat_secret": null,
        "chat_token": null,
        "chat_encoding_aes_key": null,
        "status": "NORMAL",
        "created_at": "2016-11-01 08:42:40",
        "updated_at": "2016-11-01 08:42:40",
        "corp_apps": {
          "data": [
            {
              "id": 1,
              "ep_key": "suning",
              "app_id": 2,
              "type": "chat",
              "agent_id": "2",
              "token": "token",
              "encoding_aes_key": "key",
              "permanent_code": null,
              "authorize_type": null,
              "description": "des",
              "status": "NORMAL",
              "created_at": "2016-11-01 08:44:50",
              "updated_at": "2016-11-02 15:36:10"
            }
          ]
        }
      },
      "version": "v1",
      "servertime": 1478159091
    }
     * @param $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function patchEnable($epKey)
    {
        $corp = $this->wechatCorpRepository->findWhere([
            'ep_key'=>$epKey
                                               ])
            ->first();

        if(!$corp){
            self::errorNotFound("ep_key 为 {$epKey} 的企业号不存在");
        }

        try{
            $wechatCorp = $this->wechatCorpRepository->update([
                'status'=>WechatCorp::STATUS_NORMAL
                                                ],
                                                $corp->id);
        }catch (ValidatorException $e){
            self::errorBadRequest($e->getMessageBag()->first());
        }

        return $this->response->item($wechatCorp,new WechatCorpTransformer());
    }

    /**
     * @api {PATCH} /wechat_corps/:ep_key/disable 5.设置企业号为不可用状态
     * @apiDescription 设置企业号为不可用状态
     * @apiGroup corps
     * @apiSuccessExample {json} Success-Response:
    {
        "changyi_request_id": "581aecbe7f2474.06136749",
        "inner_request_id": "581aecbe7f2474.06136749",
        "success": true,
        "status_code": 200,
        "result_code": 0,
        "message": "请求成功",
        "response": {
            "id": 1,
            "ep_key": "suning",
            "corp_id": "corp_id",
            "corp_secret": "corp_sec",
            "auth_info": "auth_info",
            "auth_fail_reason": null,
            "corp_name": "suning",
            "corp_round_logo_url": "htttp2",
            "corp_square_logo_ur": null,
            "qrcode_url": "http://qrcode",
            "chat_secret": null,
            "chat_token": null,
            "chat_encoding_aes_key": null,
            "status": "DISABLE",
            "created_at": "2016-11-01 08:42:40",
            "updated_at": "2016-11-03 15:52:30",
            "corp_apps": {
                "data": [
                    {
                        "id": 1,
                        "ep_key": "suning",
                        "app_id": 2,
                        "type": "chat",
                        "agent_id": "2",
                        "token": "token",
                        "encoding_aes_key": "key",
                        "permanent_code": null,
                        "authorize_type": null,
                        "description": "des",
                        "status": "NORMAL",
                        "created_at": "2016-11-01 08:44:50",
                        "updated_at": "2016-11-02 15:36:10"
                    }
                ]
            }
        },
        "version": "v1",
        "servertime": 1478159550
    }

     * @param $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function patchDisable($epKey)
    {
        $corp = $this->wechatCorpRepository->findWhere([
                                                           'ep_key'=>$epKey
                                                       ])
                                           ->first();

        if(!$corp){
            self::errorNotFound("ep_key 为 {$epKey} 的不存在");
        }

        try{
            $wechatCorp = $this->wechatCorpRepository->update([
                                                    'status'=>WechatCorp::STATUS_DISABLE
                                                ],
                                                $corp->id);
        }catch (ValidatorException $e){
            self::errorBadRequest($e->getMessageBag()->first());
        }


        return $this->response->item($wechatCorp,new WechatCorpTransformer());
    }


    /**
     * @api {POST} /wechat_corps/:ep_key/apps 6.创建企业号应用
     * @apiDescription 创建企业号应用
     * @apiGroup corps
     * @apiParam {String} app_id app_id
     * @apiParam {String} type 应用类型,只能是MICRO_STORE,BOSS_ASSISTANT,CASHIER_BONUS,CUSTOMER_MANAGER其中一种
     * @apiParam {String} agent_id 应用id
     * @apiParam {String} [token] token
     * @apiParam {String} [encoding_aes_key] encoding_aes_key
     * @apiParam {String} [description] description
     * @apiSuccessExample {json} Success-Response:
     * {
     * "request_id": "581841f7ac0649.62604832",
     * "msgcode": 100000,
     * "message": "请求成功",
     * "response": null,
     * "version": "v1",
     * "servertime": 1477984759
     * }
     * @apiError  203003 企业号应用已经存在
     * @param \Illuminate\Http\Request $request
     * @param                          $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function postCreateApp(Request $request,$epKey)
    {
        $input = $request->only(
            'app_id',
            'type',
            'agent_id',
            'token',
            'encoding_aes_key',
            'description'
        );

        //输入验证
        $validator = Validator::make($input,[
            'app_id'=>'required',
            'type'=>'required',
            'agent_id'=>'required',
        ],[
            'app_id.required'=>'缺少 app_id',
            'type.required'=>'缺少 type',
            'agent_id.required'=>'缺少 agent_id',
        ]);

        if($validator->fails()){
            self::errorBadRequest($validator->messages()->first());
        }

        //核实 type 类型
        if(!in_array($input['type'],WechatCorpApp::$corpAppTypes)){
            self::errorBadRequest("type 类型不合法");
        }

        $enterprise = $this->enterpriseRepository->findWhere([
                                                   'ep_key'=>$epKey
                                               ])
            ->first();

        if(!$enterprise){
            self::errorNotFound("$epKey 的企业不存在");
        }

        $exist = $this->wechatCorpAppRepository->findWhere([
            'ep_key'=>$epKey,
            'type'=>$input['type'],
            'status'=>WechatCorpApp::STATUS_NORMAL
                                                           ])
            ->first();

        if($exist){
            self::errorBadRequest("应用已经存在",ApiMessageCode::CODE_WECHAT_APP_EXIST);
        }

        try{
            $wechatApp = $this->wechatCorpAppRepository->create(
                array_merge($input,['ep_key'=>$epKey]
                ));
        }catch (ValidatorException $e){
            self::errorBadRequest($e->getMessageBag()->first());
        }catch(\Exception $e){
            self::errorInternal($e->getMessage(),$e->getCode());
        }

        return $this->response->item($wechatApp,new WechatCorpAppTransformer());
    }


    /**
     * @api {POST} /wechat_corps/:ep_key 7.创建企业号
     * @apiDescription 创建企业号
     * @apiGroup corps
     * @apiParam  {String} corp_id 企业corp_id
     * @apiParam  {String} permanent_code 永久授权码
     * @apiParam  {String} auth_info 第三方授权的微信返回的授权json
     * @apiParam  {String} corp_name 企业名称
     * @apiParam  {String} corp_round_logo_url 企业圆头像
     * @apiParam  {String} corp_square_logo_url 企业方头像
     * @apiParam  {String} qrcode_url 企业二维码
     * @apiParam  {String} [chat_secret] 聊天secret
     * @apiParam  {String} [chat_token] 聊天token
     * @apiParam  {String} [chat_encoding_aes_key] 聊天encoding_aes_key
     * @apiSuccessExample {json} Success-Response:
    {
        "changyi_request_id": "581af3a25e1097.53254645",
        "inner_request_id": "581af3a25e1097.53254645",
        "success": true,
        "status_code": 200,
        "result_code": 0,
        "message": "请求成功",
        "response": {
            "id": 3,
            "ep_key": "suning",
            "corp_id": "corp_id",
            "corp_secret": null,
            "auth_info": "info",
            "auth_fail_reason": null,
            "corp_name": "name",
            "corp_round_logo_url": "corp_square_logo_url",
            "corp_square_logo_ur": null,
            "qrcode_url": "qrcode_url",
            "chat_secret": null,
            "chat_token": null,
            "chat_encoding_aes_key": null,
            "status": "NORMAL",
            "created_at": "2016-11-03 16:21:18",
            "updated_at": "2016-11-03 16:21:18",
            "corp_apps": {
                "data": [
                    {
                        "id": 1,
                        "ep_key": "suning",
                        "app_id": 2,
                        "type": "chat",
                        "agent_id": "2",
                        "token": "token",
                        "encoding_aes_key": "key",
                        "permanent_code": null,
                        "authorize_type": null,
                        "description": "des",
                        "status": "NORMAL",
                        "created_at": "2016-11-01 08:44:50",
                        "updated_at": "2016-11-02 15:36:10"
                    }
                ]
            }
        },
        "version": "v1",
        "servertime": 1478161314
    }

     * @apiError  203001 企业号已经存在
     * @param \Illuminate\Http\Request $request
     * @param                          $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function postCreate(Request $request,$epKey)
    {
        $input = $request->only(
            'corp_id',
            'permanent_code',
            'auth_info',
            'corp_name',
            'corp_round_logo_url',
            'corp_square_logo_url',
            'qrcode_url',
            'chat_secret',
            'chat_token',
            'chat_encoding_aes_key'
            );

        //输入验证
        $validator = Validator::make($input,[
            'corp_id'=>'required',
            'permanent_code'=>'required',
            'auth_info'=>'required',
            'corp_name'=>'required',
            'corp_round_logo_url'=>'required',
            'corp_square_logo_url'=>'required',
            'qrcode_url'=>'required'
        ],[
            'corp_id.required'=>'缺少 corp_id',
            'permanent_code.required'=>'缺少 permanent_code',
            'auth_info.required'=>'缺少 auth_info',
            'corp_name.required'=>'缺少 corp_name',
            'corp_round_logo_url.required'=>'缺少 corp_round_logo_url',
            'corp_square_logo_url.required'=>'缺少 corp_square_logo_url',
            'qrcode_url.required'=>'缺少 qrcode_url',
        ]);

        if($validator->fails()){
            self::errorBadRequest($validator->messages()->first());
        }

        $enterprise = $this->enterpriseRepository->findWhere([
                                                   'ep_key'=>$epKey
                                               ])
            ->first();

        if(!$enterprise){
            self::errorNotFound("$epKey 的企业不存在");
        }

        //验证企业号是否已经存在
        $exist = $this->wechatCorpRepository->findWhere([
                                                   'ep_key'=>$epKey,
                                                   'status'=>WechatCorp::STATUS_NORMAL
                                               ])
        ->first();

        if($exist){
            self::errorBadRequest("$epKey 的企业号已经存在",ApiMessageCode::CODE_WECHAT_CORP_EXIST);
        }

        try{
            $WeChatCorp = $this->wechatCorpRepository->create(
                array_merge($input,['ep_key'=>$epKey]
                )
            );
        }catch (ValidatorException $e){
            self::errorBadRequest($e->getMessageBag()->first());
        }catch(\Exception $e){
            self::errorInternal($e->getMessage(),$e->getCode());
        }

        return $this->response->item($WeChatCorp,new WechatCorpTransformer());
    }


    /**
     * @api {PUT} /wechat_corps/:ep_key 8.修改企业号
     * @apiDescription 修改企业号
     * @apiGroup corps
     * @apiParam  {String} corp_id 企业corp_id
     * @apiParam  {String} permanent_code 永久授权码
     * @apiParam  {String} auth_info 第三方授权的微信返回的授权json
     * @apiParam  {String} corp_name 企业名称
     * @apiParam  {String} corp_round_logo_url 企业圆头像
     * @apiParam  {String} corp_square_logo_url 企业方头像
     * @apiParam  {String} qrcode_url 企业二维码
     * @apiParam  {String} [chat_secret] 聊天secret
     * @apiParam  {String} [chat_token] 聊天token
     * @apiParam  {String} [chat_encoding_aes_key] 聊天encoding_aes_key
     * @apiSuccessExample {json} Success-Response:
    {
        "changyi_request_id": "581af3a25e1097.53254645",
        "inner_request_id": "581af3a25e1097.53254645",
        "success": true,
        "status_code": 200,
        "result_code": 0,
        "message": "请求成功",
        "response": {
            "id": 3,
            "ep_key": "suning",
            "corp_id": "corp_id",
            "corp_secret": null,
            "auth_info": "info",
            "auth_fail_reason": null,
            "corp_name": "name",
            "corp_round_logo_url": "corp_square_logo_url",
            "corp_square_logo_ur": null,
            "qrcode_url": "qrcode_url",
            "chat_secret": null,
            "chat_token": null,
            "chat_encoding_aes_key": null,
            "status": "NORMAL",
            "created_at": "2016-11-03 16:21:18",
            "updated_at": "2016-11-03 16:21:18",
            "corp_apps": {
                "data": [
                    {
                        "id": 1,
                        "ep_key": "suning",
                        "app_id": 2,
                        "type": "chat",
                        "agent_id": "2",
                        "token": "token",
                        "encoding_aes_key": "key",
                        "permanent_code": null,
                        "authorize_type": null,
                        "description": "des",
                        "status": "NORMAL",
                        "created_at": "2016-11-01 08:44:50",
                        "updated_at": "2016-11-02 15:36:10"
                    }
                ]
            }
        },
        "version": "v1",
        "servertime": 1478161314
    }

     * @param \Illuminate\Http\Request $request
     * @param                          $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function putModify(Request $request,$epKey)
    {
        $input = $request->only(
            'corp_id',
            'permanent_code',
            'auth_info',
            'corp_name',
            'corp_round_logo_url',
            'corp_square_logo_url',
            'qrcode_url',
            'chat_secret',
            'chat_token',
            'chat_encoding_aes_key'
        );

        //输入验证
        $validator = Validator::make($input,[
            'corp_id'=>'required',
            'permanent_code'=>'required',
            'auth_info'=>'required',
            'corp_name'=>'required',
            'corp_round_logo_url'=>'required',
            'corp_square_logo_url'=>'required',
            'qrcode_url'=>'required'
        ],[
            'corp_id.required'=>'缺少 corp_id',
            'permanent_code.required'=>'缺少 permanent_code',
            'auth_info.required'=>'缺少 auth_info',
            'corp_name.required'=>'缺少 corp_name',
            'corp_round_logo_url.required'=>'缺少 corp_round_logo_url',
            'corp_square_logo_url.required'=>'缺少 corp_square_logo_url',
            'qrcode_url.required'=>'缺少 qrcode_url',
        ]);

        if($validator->fails()){
            self::errorBadRequest($validator->messages()->first());
        }


        //验证企业号是否已经存在
        $exist = $this->wechatCorpRepository->findWhere([
                                                            'ep_key'=>$epKey,
                                                            'status'=>WechatCorp::STATUS_NORMAL
                                                        ])
                                            ->first();

        if(!$exist){
            self::errorBadRequest("$epKey 的企业号不存在");
        }

        try{
            $WeChatCorp = $this->wechatCorpRepository->update(array_merge(
                                                    $input,
                                                    [
                                                        'ep_key'=>$epKey
                                                    ]),$exist->id
            );
        }catch (ValidatorException $e){
            self::errorBadRequest($e->getMessageBag()->first());
        }catch(\Exception $e){
            self::errorInternal($e->getMessage(),$e->getCode());
        }

        return $this->response->item($WeChatCorp,new WechatCorpTransformer());
    }


    /**
     * @api {GET} /wechat_corps/:ep_key 9.企业号详情
     * @apiDescription 企业号详情
     * @apiGroup corps
     * @apiSuccessExample {json} Success-Response:
    {
      "changyi_request_id": "581af435cd88e1.64582732",
      "inner_request_id": "581af435cd88e1.64582732",
      "success": true,
      "status_code": 200,
      "result_code": 0,
      "message": "请求成功",
      "response": {
        "id": 3,
        "ep_key": "suning",
        "corp_id": "corp_id",
        "corp_secret": null,
        "auth_info": "info",
        "auth_fail_reason": null,
        "corp_name": "name",
        "corp_round_logo_url": "corp_square_logo_url",
        "corp_square_logo_ur": null,
        "qrcode_url": "qrcode_url",
        "chat_secret": null,
        "chat_token": null,
        "chat_encoding_aes_key": null,
        "status": "NORMAL",
        "created_at": "2016-11-03 16:21:18",
        "updated_at": "2016-11-03 16:21:18",
        "corp_apps": {
          "data": [
            {
              "id": 1,
              "ep_key": "suning",
              "app_id": 2,
              "type": "chat",
              "agent_id": "2",
              "token": "token",
              "encoding_aes_key": "key",
              "permanent_code": null,
              "authorize_type": null,
              "description": "des",
              "status": "NORMAL",
              "created_at": "2016-11-01 08:44:50",
              "updated_at": "2016-11-02 15:36:10"
            }
          ]
        }
      },
      "version": "v1",
      "servertime": 1478161461
    }
     * @param $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function getShow($epKey)
    {
        $corp = $this->wechatCorpRepository->findWhere([
                                                           'ep_key'=>$epKey,
                                                           'status'=>WechatCorp::STATUS_NORMAL
                                                       ])
        ->first();
        if(!$corp){
            self::errorNotFound("$epKey 的企业不存在", ApiMessageCode::CODE_ENTERPRISE_NOT_EXIST);
        }

        return $this->response->item($corp,new WechatCorpTransformer());
    }



}