<?php

namespace App\Console\Commands;

use App\Libraries\Support\PubsubAbility;
use Illuminate\Console\Command;

class KafkaPubTest extends Command
{
    use PubsubAbility;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'kafka-pub-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'kafka 发布测试';

    /**
     * Create a new command instance.
     *
     * @return \Orangehill\Iseed\IseedCommand
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
//        try {
//            $response = app('pubsub')->publish('liucaix', ['type' => 'type', 'data' => ['ep_key' => 'suning']]);
//        } catch (\Exception $e) {
//            dd($e->getMessage());
//        }

        $this->subscribe('liucaix',function($message){
            dd($message);
        });

        //dd($response);
    }

}
