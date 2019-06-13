<?php

namespace App\Console\Commands;

use App\Handlers\OrgGoodHandler;
use Illuminate\Console\Command;
use Ml\Response\Result;

class MockOrgGoods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mock:org_goods {id?}{num?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建模拟原商品数据【参数 原商品ID（必传）、添加条数（默认2条）】';
    private $request;
    private $result;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->request = new \Illuminate\Http\Request();
        $this->result = new Result();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start_at = microtime(true);
        $this->process();
        $this->info('used_time:' . (microtime(true) - $start_at));
    }

    /**
     * 虚构商品名称
     * @return array
     */
    public function frakeData()
    {
        return ['韩国春雨蜂蜜面膜 ', 'Dr.Morita森田玻尿酸复合面膜 99RMB/10片', 'minon氨基酸保湿面膜 120RMB/4片 ', 'Charlotte Tilbury 2019新品限定唇釉色', '迪凯瑞VC水乳', '荷诺益生菌水乳', '宿系之源原浆小姐'];
    }

    /**
     *
     */
    public function process()
    {
        $id = $this->argument('id') ?: 0; ## 商品ID
        $num = $this->argument('num') ?: 2; ## 添加数量
        if (!$id) {
            $this->error('请传一个原商品的ID值');
            return;
        }
        $orgGood = app(OrgGoodHandler::class);
        $this->request->offsetSet('org_goods_id', $id);## 商品ID

        for ($i = 0; $i < $num; $i++) {
            $this->request->offsetSet('name', $this->frakeData()[rand(0, 6)]);
            $orgGood->copyFormOrg($this->request);

        }
        echo 'run script ' . __CLASS__ . ' end !' . PHP_EOL . ',count:' . $i . PHP_EOL;

    }

}
