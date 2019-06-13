<?php

namespace App\Console\Commands;

use App\Handlers\RefundHandler;
use App\Models\Refund;
use Illuminate\Console\Command;

class RefundProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refunds:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '处理超时退款订单';
    private $refundHandler;
    private $request;

    /**
     * Create a new command instance.
     *
     * @param RefundHandler $refundHandler
     */
    public function __construct(RefundHandler $refundHandler)
    {
        $this->refundHandler = $refundHandler;
        $this->request = new \Illuminate\Http\Request();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->process();
    }

    /**
     * 申请中的退款订单  超24小时 自动更改为售后处理
     *
     */
    public function process()
    {

        ## 24时(h)=86400秒(s)
        $count = 0;


        ## 正在申请中的退款订单
        $refund = ["id" => "ID", "created_at" => "申请时间"];
        Refund::where('refund_progress', Refund::REFUND_PROGRESS_APPLYING)->orderBy('id', 'asc')->chunk(100, function ($refundApplyings) use ($refund, &$count) {

            foreach ($refundApplyings as $refundApplying) {
                $poor_time = time() - strtotime($refundApplying['created_at']);
                if ($poor_time >= 86400) {
                    $this->request->offsetSet('id', $refundApplying['id']); ## 退款订单ID
                    $this->refundHandler->after_sales($this->request);
                }
                $count++;
                echo  PHP_EOL;

            }

        });

    }

}
