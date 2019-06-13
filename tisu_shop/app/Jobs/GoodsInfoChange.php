<?php

namespace App\Jobs;

use App\Models\OrgGood;
use App\Models\Goods;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GoodsInfoChange implements ShouldQueue
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

    protected $orgGood;

    /**
     * Create a new job instance.
     *
     * GoodsInfoChange constructor.
     * @param OrgGood $orgGood
     */
    public function __construct(OrgGood $orgGood)
    {
        $this->orgGood = $orgGood;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ### 业务逻辑处理


        // 通过事务执行 sql
        \DB::transaction(function () {
            // 循环遍历归属于原商品的所有Goods表数据
            foreach ($this->orgGood->goods as $item) {

                ## 修改商品发布状态  为信息变更
                Goods::where('id',$item->id)->update(['publish_status'=>Goods::PUBLISH_STATUS_INFO_CHANGE]);
                \Log::info(__CLASS__ . '::' . __FUNCTION__ . ':' . print_r($item->toArray(), true));
            }
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
