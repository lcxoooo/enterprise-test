<?php
/**
 * Created by PhpStorm.
 * User: JohnWang <takato@vip.qq.com>
 * Date: 2016/10/31
 * Time: 15:24
 */

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jhk\Enterprise\Enterprise;
use Jhk\Enterprise\EnterpriseCorp;
use Jhk\Enterprise\EnterpriseMp;
use Jhk\Enterprise\Exceptions\HttpException;
use Jhk\Enterprise\Setting;
use Jhk\Enterprise\Sms;

class SdkDebugController extends Controller
{

    public function __construct()
    {
        if(env('APP_ENV') !== 'local'){
            echo "非法访问";
            exit(-1);
        }
    }


    public function debug(Sms $sms,Setting $setting,EnterpriseCorp $corp,Enterprise $enterprise,EnterpriseMp $enterpriseMp,Request $request)
    {
        $epKey = 'suning';
        dd($sms->detail($epKey));


        dd($sms->records($epKey));

        dd($setting->setSetting($epKey,'a','dd'));
        dd($setting->getSetting($epKey,'a'));
        dd($corp->detail($epKey));
        dd($corp->disable($epKey));
        dd($corp->index());

        dd($corp->modify($epKey,[
            'corp_id'=>'copr_id',
            'permanent_code'=>'permanent_code',
            'auth_info'=>'auth_info',
            'corp_name'=>'corp_name',
            'corp_round_logo_url'=>'corp_round_logo_url',
            'corp_square_logo_url'=>'corp_square_logo_url',
            'qrcode_url'=>'qrcode_url',
        ]));

        dd($corp->createApp($epKey,[
            'app_id'=>1,
            'type'=>"MICRO_STORE",
            'agent_id'=>1
        ]));

        dd($corp->create($epKey,[
            'corp_id'=>'copr_id',
            'permanent_code'=>'permanent_code',
            'auth_info'=>'auth_info',
            'corp_name'=>'corp_name',
            'corp_round_logo_url'=>'corp_round_logo_url',
            'corp_square_logo_url'=>'corp_square_logo_url',
            'qrcode_url'=>'qrcode_url',
        ]));
        dd($corp->setTicket('eee'));
        dd($corp->getTicket());
        dd($enterpriseMp->modify($epKey,[
            'app_id'=>'2appid',
            'component_refresh_token'=>'2component_refresh_token',
            'nick_name'=>'nick_name',
            'head_img'=>'head_img',
            'original_id'=>'original_id',
            'alias'=>'alias',
            'qrcode_url'=>'qrcode_url',
        ]));


        dd($enterpriseMp->create($epKey,[
            'app_id'=>'2appid',
            'component_refresh_token'=>'2component_refresh_token',
            'nick_name'=>'nick_name',
            'head_img'=>'head_img',
            'original_id'=>'original_id',
            'alias'=>'alias',
            'qrcode_url'=>'qrcode_url',
        ]));

        dd($enterpriseMp->detail($epKey));

        dd($enterpriseMp->setTicket('333'));

        dd($enterpriseMp->getTicket());

        dd($enterprise->enable('suning'));

        $enterprises = $enterprise->index($request->all());


        try{
            dd($enterprise->login('18662608681','123456'));
        }catch (HttpException $e){
            if($e->getCode() === 202010){
                echo "账号密码错误";
            }
        }

        try{
            dd($enterprise->detail('suning2'));
        }catch (HttpException $e){
            dd($e->getMessage());
        }


        dd($enterprise->modify('suning',[
            'email'=>'4422@qq.com',
            'mobile'=>'18662601777',
            'realname'=>'zq',
            'company_name'=>"xiaoppu"
        ]));


        try{
            dd($enterprise->disable('suning'));
        }catch (HttpException $e){
            dd($e->getMessage());
        }
//

    }

}