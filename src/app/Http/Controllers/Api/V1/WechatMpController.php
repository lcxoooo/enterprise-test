<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/10/31
 * Time: 15:25
 */

namespace App\Http\Controllers\Api\V1;


use App\MessageCodes\ApiMessageCode;
use App\Models\WechatMp;
use App\Models\WechatTicket;
use App\Repositories\WechatMpRepository;
use App\Repositories\WechatTicketRepository;
use App\Repositories\EnterpriseRepository;
use App\Transformers\WechatMpTransformer;
use App\Transformers\WechatTicketsTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WechatMpController extends BaseController
{

    protected $wechatTicketRepositoryEloquent;
    protected $enterpriseRepository;
    protected $wechatMpRepository;

    public function __construct(
        WechatMpRepository $wechatMpRepository,
        WechatTicketRepository $wechatTicketRepositoryEloquent,
        EnterpriseRepository $enterpriseRepository
    ) {
        $this->wechatMpRepository             = $wechatMpRepository;
        $this->wechatTicketRepositoryEloquent = $wechatTicketRepositoryEloquent;
        $this->enterpriseRepository           = $enterpriseRepository;
    }

    /**
     * @api {GET} /wechat_mps/ticket 1.获取服务号开放平台Tickets
     * @apiDescription 获取服务号开放平台Tickets
     * @apiGroup mps
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
            "type": "MP",
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
            'type' => WechatTicket::TYPE_MP
        ])
                                                       ->first();


        if (!$ticket) {
            self::errorNotFound('Ticket 不存在');
        }

        return $this->response->item($ticket, new WechatTicketsTransformer());
    }


    /**
     * @api {POST} /wechat_mps/ticket 2.设置服务号开放平台Tickets
     * @apiDescription 设置服务号开放平台Tickets
     * @apiGroup mps
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
            "type": "MP",
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

        if (!$ticket) {
            self::errorBadRequest('缺少 Ticket 值');
        }

        $ticket = $this->wechatTicketRepositoryEloquent->updateOrCreate([
            'type' => WechatTicket::TYPE_MP
        ], [
            'type'   => WechatTicket::TYPE_MP,
            'ticket' => $ticket
        ]);

        return $this->response->item($ticket, new WechatTicketsTransformer());
    }


    /**
     * @api {GET} /wechat_mps 3.服务号列表
     * @apiDescription 服务号列表
     * @apiGroup mps
     * @apiParam  {Number} [pagesize=20] 没有显示条数
     * @apiParam  {Number} [page=1] 当前页码
     * @apiSuccessExample {json} Success-Response:
        {
          "changyi_request_id": "581af8d52f8b83.26507981",
          "inner_request_id": "581af8d52f8b83.26507981",
          "success": true,
          "status_code": 200,
          "result_code": 0,
          "message": "请求成功",
          "response": {
            "data": [
              {
                "id": 3,
                "ep_key": "suning",
                "app_id": "appid",
                "app_secret": null,
                "server_token": null,
                "encoding_aes_key": null,
                "component_refresh_token": "token",
                "authorize_type": "",
                "nick_name": "nick_name",
                "head_img": "head_img",
                "original_id": "original_id",
                "alias": "alias",
                "qrcode_url": "qrcode_url",
                "status": "NORMAL",
                "is_edit_menu_item": false,
                "created_at": "2016-11-03 16:40:32",
                "updated_at": "2016-11-03 16:43:10"
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
          "servertime": 1478162645
        }
     * @param \Illuminate\Http\Request $request
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function index(Request $request)
    {
        $pagesize = $request->has('pagesize') ? $request->get('pagesize') : 20;
        return $this->response->paginator($this->wechatMpRepository->paginate($pagesize), new WechatMpTransformer());
    }


    /**
     * @api {PATCH} /wechat_mps/:ep_key/enable 4.设置服务号状态为可用
     * @apiDescription 设置服务号状态为可用
     * @apiGroup mps
     * @apiSuccessExample {json} Success-Response:
        {
            "changyi_request_id": "581af63248b820.08445028",
            "inner_request_id": "581af63248b820.08445028",
            "success": true,
            "status_code": 200,
            "result_code": 0,
            "message": "请求成功",
            "response": {
                "id": 1,
                "ep_key": "suning",
                "app_id": "appid",
                "app_secret": "appsec",
                "server_token": "tokne",
                "encoding_aes_key": null,
                "component_refresh_token": null,
                "authorize_type": "type",
                "nick_name": "nickname",
                "head_img": "image",
                "original_id": "originId",
                "alias": "alias",
                "qrcode_url": "url",
                "status": "NORMAL",
                "is_edit_menu_item": false,
                "created_at": "2016-11-03 02:09:25",
                "updated_at": "2016-11-03 16:32:33"
            },
            "version": "v1",
            "servertime": 1478161970
        }
     * @param $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function patchEnable($epKey)
    {
        $mp = $this->wechatMpRepository->findWhere([
            'ep_key' => $epKey
        ])
                                       ->first();

        if (!$mp) {
            self::errorNotFound("ep_key 为 {$epKey} 的服务号不存在");
        }

        try{
            $mp = $this->wechatMpRepository->update([
                        'status' => WechatMp::STATUS_NORMAL
                    ], $mp->id);
        }catch (\Exception $e){
            self::errorInternal($e->getMessage());
        }

        return $this->response->item($mp, new WechatMpTransformer());
    }

    /**
     * @api {PATCH} /wechat_mps/:ep_key/disable 5.设置服务号状态为不可用
     * @apiDescription 设置服务号状态为不可用
     * @apiGroup mps
     * @apiSuccessExample {json} Success-Response:
        {
            "changyi_request_id": "581af63248b820.08445028",
            "inner_request_id": "581af63248b820.08445028",
            "success": true,
            "status_code": 200,
            "result_code": 0,
            "message": "请求成功",
            "response": {
                "id": 1,
                "ep_key": "suning",
                "app_id": "appid",
                "app_secret": "appsec",
                "server_token": "tokne",
                "encoding_aes_key": null,
                "component_refresh_token": null,
                "authorize_type": "type",
                "nick_name": "nickname",
                "head_img": "image",
                "original_id": "originId",
                "alias": "alias",
                "qrcode_url": "url",
                "status": "DISABLE",
                "is_edit_menu_item": false,
                "created_at": "2016-11-03 02:09:25",
                "updated_at": "2016-11-03 16:32:33"
            },
            "version": "v1",
            "servertime": 1478161970
        }
     * @param $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function patchDisable($epKey)
    {
        $mp = $this->wechatMpRepository->findWhere([
            'ep_key' => $epKey
        ])
                                       ->first();

        if (!$mp) {
            self::errorNotFound("ep_key 为 {$epKey} 的服务号不存在");
        }

        try{
            $mp = $this->wechatMpRepository->update([
                        'status' => WechatMp::STATUS_DISABLE
                    ], $mp->id);
        }catch (\Exception $e){
            self::errorInternal($e->getMessage());
        }


        return $this->response->item($mp, new WechatMpTransformer());
    }

    /**
     * @api {GET} /wechat_mps/:ep_key 6.服务号详情
     * @apiDescription 服务号详情
     * @apiGroup mps
     * @apiSuccessExample {json} Success-Response:
        {
          "changyi_request_id": "581af6a5c300c5.12660258",
          "inner_request_id": "581af6a5c300c5.12660258",
          "success": true,
          "status_code": 200,
          "result_code": 0,
          "message": "请求成功",
          "response": {
            "id": 1,
            "ep_key": "suning",
            "app_id": "appid",
            "app_secret": "appsec",
            "server_token": "tokne",
            "encoding_aes_key": null,
            "component_refresh_token": null,
            "authorize_type": "type",
            "nick_name": "nickname",
            "head_img": "image",
            "original_id": "originId",
            "alias": "alias",
            "qrcode_url": "url",
            "status": "DISABLE",
            "is_edit_menu_item": false,
            "created_at": "2016-11-03 02:09:25",
            "updated_at": "2016-11-03 16:33:37"
          },
          "version": "v1",
          "servertime": 1478162085
        }
     * @apiError {type} field description
     * @param $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function getShow($epKey)
    {
        $mp = $this->wechatMpRepository->findWhere([
            'ep_key' => $epKey
        ])
                                       ->first();
        if (!$mp) {
            self::errorNotFound("$epKey 的企业不存在");
        }

        return $this->response->item($mp, new WechatMpTransformer());
    }


    /**
     * @api {POST} /wechat_mps/:ep_key 7.新建服务号
     * @apiDescription 新建服务号
     * @apiGroup mps
     * @apiParam  {String} app_id 服务号app_id
     * @apiParam  {String} component_refresh_token 服务号授权component_refresh_token
     * @apiParam  {String} nick_name 服务号授权nick_name
     * @apiParam  {String} head_img 服务号授权head_img
     * @apiParam  {String} original_id 服务号授权original_id
     * @apiParam  {String} alias 服务号授权alias
     * @apiParam  {String} qrcode_url 服务号授权qrcode_url
     * @apiSuccessExample {json} Success-Response:
        {
            "changyi_request_id": "581af800490517.18904231",
            "inner_request_id": "581af800490517.18904231",
            "success": true,
            "status_code": 200,
            "result_code": 0,
            "message": "请求成功",
            "response": {
                "id": 3,
                "ep_key": "suning",
                "app_id": "appid",
                "app_secret": null,
                "server_token": null,
                "encoding_aes_key": null,
                "component_refresh_token": "token",
                "authorize_type": null,
                "nick_name": "nick_name",
                "head_img": "head_img",
                "original_id": "original_id",
                "alias": "alias",
                "qrcode_url": "qrcode_url",
                "status": null,
                "is_edit_menu_item": false,
                "created_at": "2016-11-03 16:40:32",
                "updated_at": "2016-11-03 16:40:32"
            },
            "version": "v1",
            "servertime": 1478162432
        }
     * @apiError  204001 服务号已经存在
     * @param \Illuminate\Http\Request $request
     * @param                          $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function postCreate(Request $request, $epKey)
    {
        $input = $request->only(
            'app_id',
            'component_refresh_token',
            'nick_name',
            'head_img',
            'original_id',
            'alias',
            'qrcode_url'
        );

        $validator = Validator::make($input,[
            'app_id'=>'required',
            'component_refresh_token'=>'required',
            'nick_name'=>'required',
            'head_img'=>'required',
            'original_id'=>'required',
            'alias'=>'required',
            'qrcode_url'=>'required'
        ],[
            'app_id.required'=>'缺少 app_id',
            'component_refresh_token.required'=>'缺少 component_refresh_token',
            'nick_name.required'=>'缺少 nick_name',
            'head_img.required'=>'缺少 head_img',
            'original_id.required'=>'缺少 original_id',
            'alias.required'=>'缺少 alias',
            'qrcode_url.required'=>'缺少 qrcode_url',
        ]);

        if($validator->fails()){
            self::errorBadRequest($validator->messages()->first());
        }

        //验证是否已经存在
        $mp = $this->wechatMpRepository->findWhere([
            'ep_key' => $epKey,
            'status' => WechatMp::STATUS_NORMAL
        ])->first();

        if ($mp) {
            self::errorBadRequest("{$epKey} 的服务号已经存在", ApiMessageCode::CODE_WECHAT_MP_EXIST);
        }

        try {
            $mp = $this->wechatMpRepository->create(array_merge($input, [
                'ep_key' => $epKey
            ]));
        } catch (\Exception $e) {
            self::errorInternal($e->getMessage());
        }

        return $this->response->item($mp, new WechatMpTransformer());

    }

    /**
     * @api {PUT} /wechat_mps/:ep_key 8.修改服务号
     * @apiDescription 修改服务号
     * @apiGroup mps
     * @apiParam  {String} app_id 服务号app_id
     * @apiParam  {String} component_refresh_token 服务号授权component_refresh_token
     * @apiParam  {String} nick_name 服务号授权nick_name
     * @apiParam  {String} head_img 服务号授权head_img
     * @apiParam  {String} original_id 服务号授权original_id
     * @apiParam  {String} alias 服务号授权alias
     * @apiParam  {String} qrcode_url 服务号授权qrcode_url
     * @apiSuccessExample {json} Success-Response:
        {
            "changyi_request_id": "581af800490517.18904231",
            "inner_request_id": "581af800490517.18904231",
            "success": true,
            "status_code": 200,
            "result_code": 0,
            "message": "请求成功",
            "response": {
                "id": 3,
                "ep_key": "suning",
                "app_id": "appid",
                "app_secret": null,
                "server_token": null,
                "encoding_aes_key": null,
                "component_refresh_token": "token",
                "authorize_type": null,
                "nick_name": "nick_name",
                "head_img": "head_img",
                "original_id": "original_id",
                "alias": "alias",
                "qrcode_url": "qrcode_url",
                "status": null,
                "is_edit_menu_item": false,
                "created_at": "2016-11-03 16:40:32",
                "updated_at": "2016-11-03 16:40:32"
            },
            "version": "v1",
            "servertime": 1478162432
        }
     * @apiError  204001 服务号已经存在
     * @param \Illuminate\Http\Request $request
     * @param                          $epKey
     * @return \Dingo\Api\Http\Response
     * @author zhuzhengqian@vchangyi.com
     */
    public function putModify(Request $request, $epKey)
    {
        $input = $request->only(
            'app_id',
            'app_secret',
            'server_token',
            'encoding_aes_key',
            'component_refresh_token',
            'authorize_type',
            'nick_name',
            'head_img',
            'original_id',
            'alias',
            'qrcode_url'
        );

        $validator = Validator::make($input,[
            'app_id'=>'required',
            'component_refresh_token'=>'required',
            'nick_name'=>'required',
            'head_img'=>'required',
            'original_id'=>'required',
            'alias'=>'required',
            'qrcode_url'=>'required'
        ],[
            'app_id.required'=>'缺少 app_id',
            'component_refresh_token.required'=>'缺少 component_refresh_token',
            'nick_name.required'=>'缺少 nick_name',
            'head_img.required'=>'缺少 head_img',
            'original_id.required'=>'缺少 original_id',
            'alias.required'=>'缺少 alias',
            'qrcode_url.required'=>'缺少 qrcode_url',
        ]);

        if($validator->fails()){
            self::errorBadRequest($validator->messages()->first());
        }

        //验证是否已经存在
        $mp = $this->wechatMpRepository->findWhere([
            'ep_key' => $epKey,
            'status' => WechatMp::STATUS_NORMAL
        ])->first();

        if (!$mp) {
            self::errorBadRequest("{$epKey} 的服务号不存在", ApiMessageCode::CODE_WECHAT_MP_NOT_EXIST);
        }

        try {
            $mp = $this->wechatMpRepository->update($input, $mp->id);
        } catch (\Exception $e) {
            self::errorInternal($e->getMessage());
        }
        return $this->response->item($mp, new WechatMpTransformer());
    }

}