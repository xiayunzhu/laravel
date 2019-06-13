<?php

namespace App\Console\Commands;

use function Composer\Autoload\includeFile;
use Illuminate\Console\Command;

class RegionExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'region:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '区域编码表数据导出';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $datas = [];
        \DB::table('regions')->orderBy('id', 'asc')->chunk(200, function ($regions) use (&$datas) {
            foreach ($regions as $region) {

                $row = json_decode(json_encode($region), true);//['id' => $region->id, 'pid' => $region->pid, 'shortname' => $region->shortname]

                $strArr = [];
                foreach ($row as $key => $value) {
                    $strArr[] = $key . '=' . $value;
                }
                $datas[] = implode('&', $strArr);
            }
        });

//        file_put_contents(storage_path('regions.txt'), implode("\n", $datas));

    }
}
