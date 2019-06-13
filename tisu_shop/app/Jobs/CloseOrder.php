<?php

namespace App\Jobs;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CloseOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * 任务最大尝试次数。
     *
     * @var int
     */
    public $tries = 3;

    /**
     * 任务运行的超时时间。
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * @var Order
     */
    protected $order;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     * @param int $delay 延迟的时间 (s)
     */
    public function __construct(Order $order, $delay)
    {
        //
        $this->order = $order;

        $this->delay($delay);// 设置延迟的时间，delay() 方法的参数代表多少秒之后执行
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ### 业务逻辑处理

        // 判断对应的订单是否已经被支付
        // 如果已经支付则不需要关闭订单，直接退出
        if ($this->order->pay_time) {
            return;
        }

        // 通过事务执行 sql
        \DB::transaction(function () {
            // 将订单的 order_status 字段标记为 CLOSE，即关闭订单
            $this->order->update(['close_time' => time(), 'order_status' => Order::ORDER_STATUS_CLOSE]);
            // 循环遍历订单中的商品 SKU，将订单中的数量加回到 SKU 的库存中去 (业务需求;要求15分钟未付款占用库存的时间小于)
        });


    }

    /**
     * 要处理的失败任务。
     *
     * @param \Exception $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        // 给用户发送失败通知，等等...
        \Log::info(__CLASS__ . '::' . __FUNCTION__ . ':' . $exception->getMessage());
    }
}
