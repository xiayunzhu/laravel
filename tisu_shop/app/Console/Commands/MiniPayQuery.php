<?php

namespace App\Console\Commands;

use App\Handlers\PayHandler;
use App\Models\Wxapp;
use Illuminate\Console\Command;

class MiniPayQuery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'min:pay-query {transaction_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '查询订单的付款清空';

    private $payHander;

    /**
     * Create a new command instance.
     *
     * @param PayHandler $payHandler
     */
    public function __construct(PayHandler $payHandler)
    {
        $this->payHander = $payHandler;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \App\Lib\Wx\MinPay\Exception\MiniPayException
     * @throws \App\Lib\Wx\MinPay\Exception\SandboxException
     * @throws \ErrorException
     */
    public function handle()
    {
        //
        $transaction_id = $this->argument('transaction_id');

        $app_id = 'wx7ba43f874a4a6516';
        $wxapp = Wxapp::where('app_id', $app_id)->first();
        if (empty($wxapp)) {
            $this->error('商城不存在');
        }

        $data = $this->payHander->query($wxapp, $transaction_id);

        $this->info(print_r($data,true));

    }
}
