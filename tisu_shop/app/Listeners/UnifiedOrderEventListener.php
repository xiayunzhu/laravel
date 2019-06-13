<?php

namespace App\Listeners;

use App\Events\UnifiedOrderEvent;
use App\Models\WxPayReport;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UnifiedOrderEventListener implements ShouldQueue
{
    /**
     * 任务应该发送到的队列的名称
     * @var string|null
     */
    public $queue = 'wxPayReport';

    /**
     * 任务最大尝试次数
     * @var int
     */
    public $tries = 3;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UnifiedOrderEvent $event
     * @return void
     */
    public function handle(UnifiedOrderEvent $event)
    {
        //获取监听到的数据包 -- 统一下单返回结果
        $unifiedOrderResult = $event->getUnifiedOrderResult();

        $model = WxPayReport::create($unifiedOrderResult);
        if (!$model) {
            \Log::info(__CLASS__ . ':' . json_encode($unifiedOrderResult));
        }
    }
}
