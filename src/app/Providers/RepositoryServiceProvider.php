<?php

namespace App\Providers;

use App\Repositories\PubsubJobRepository;
use App\Repositories\PubsubJobRepositoryEloquent;
use App\Repositories\EnterpriseRepository;
use App\Repositories\EnterpriseRepositoryEloquent;
use App\Repositories\EnterpriseSettingRepository;
use App\Repositories\EnterpriseSettingRepositoryEloquent;
use App\Repositories\EnterpriseSmsQuotaLogRepository;
use App\Repositories\EnterpriseSmsQuotaLogRepositoryEloquent;
use App\Repositories\EnterpriseSmsRecordRepository;
use App\Repositories\EnterpriseSmsRecordRepositoryEloquent;
use App\Repositories\EnterpriseSmsQuotaRepository;
use App\Repositories\EnterpriseSmsQuotaRepositoryEloquent;
use App\Repositories\WechatCorpAppRepository;
use App\Repositories\WechatCorpAppRepositoryEloquent;
use App\Repositories\WechatCorpRepository;
use App\Repositories\WechatCorpRepositoryEloquent;
use App\Repositories\WechatMpMenuEventRepository;
use App\Repositories\WechatMpMenuEventRepositoryEloquent;
use App\Repositories\WechatMpRepository;
use App\Repositories\WechatMpRepositoryEloquent;
use App\Repositories\WechatMpTemplateRepository;
use App\Repositories\WechatMpTemplateRepositoryEloquent;
use App\Repositories\WechatTicketRepository;
use App\Repositories\WechatTicketRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 *
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    	$this->app->bind(PubsubJobRepository::class, PubsubJobRepositoryEloquent::class);
        $this->app->bind(EnterpriseRepository::class, EnterpriseRepositoryEloquent::class);
        $this->app->bind(EnterpriseSettingRepository::class, EnterpriseSettingRepositoryEloquent::class);
        $this->app->bind(EnterpriseSmsQuotaRepository::class, EnterpriseSmsQuotaRepositoryEloquent::class);
        $this->app->bind(EnterpriseSmsRecordRepository::class, EnterpriseSmsRecordRepositoryEloquent::class);
        $this->app->bind(EnterpriseSmsQuotaLogRepository::class, EnterpriseSmsQuotaLogRepositoryEloquent::class);
        $this->app->bind(WechatCorpRepository::class, WechatCorpRepositoryEloquent::class);
        $this->app->bind(WechatCorpAppRepository::class, WechatCorpAppRepositoryEloquent::class);
        $this->app->bind(WechatMpRepository::class, WechatMpRepositoryEloquent::class);
        $this->app->bind(WechatMpMenuEventRepository::class, WechatMpMenuEventRepositoryEloquent::class);
        $this->app->bind(WechatMpTemplateRepository::class, WechatMpTemplateRepositoryEloquent::class);
        $this->app->bind(WechatTicketRepository::class, WechatTicketRepositoryEloquent::class);
    }
}
