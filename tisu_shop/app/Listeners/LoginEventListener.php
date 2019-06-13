<?php

namespace App\Listeners;

use App\Events\LoginEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoginEventListener implements ShouldQueue
{

    /**
     * 任务应该发送到的队列的名称
     * @var string|null
     */
    public $queue = 'loginEventListener';

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
     * @param  LoginEvent $event
     * @return void
     */
    public function handle(LoginEvent $event)
    {
        //
        try {
            //获取事件中保存的信息
            $user = $event->getUser();
            $agent = $event->getAgent();
            $ip = $event->getIp();
            $timestamp = $event->getTimestamp();

            //登录信息
            $login_info = [
                'ip' => $ip,
                'login_time' => $timestamp,
                'user_id' => $user->id,
                'user_name' => $user->name,
            ];

            // zhuzhichao/ip-location-zh 包含的方法获取ip地理位置
            $addresses = \Ip::find($ip);
            $login_info['address'] = implode(' ', $addresses);

            // jenssegers/agent 的方法来提取agent信息
            $login_info['device'] = $agent->device(); //设备名称
            $browser = $agent->browser();
            $login_info['browser'] = $browser . ' ' . $agent->version($browser); //浏览器
            $platform = $agent->platform();
            $login_info['platform'] = $platform . ' ' . $agent->version($platform); //操作系统
            $login_info['language'] = implode(',', $agent->languages()); //语言
            //设备类型
            if ($agent->isTablet()) {
                // 平板
                $login_info['device_type'] = 'tablet';
            } else if ($agent->isMobile()) {
                // 便捷设备
                $login_info['device_type'] = 'mobile';
            } else if ($agent->isRobot()) {
                // 爬虫机器人
                $login_info['device_type'] = 'robot';
                $login_info['device'] = $agent->robot(); //机器人名称
            } else {
                // 桌面设备
                $login_info['device_type'] = 'desktop';
            }

            $login_info['created_at'] = date('Y-m-d H:i:s', $timestamp);
            $login_info['updated_at'] = date('Y-m-d H:i:s', $timestamp);

            //插入到数据库
            \DB::table('login_logs')->insert($login_info);

            ##更新用户表的最后登录时间和IP
            $user->update(['login_at' => $timestamp, 'login_ip' => $ip]);
            $user->save();

        } catch (\Exception $exception) {
            \Log::info('[' . date('Y-m-d H:i:s') . ']' . __CLASS__ . '-' . __FUNCTION__ . '-' . $exception->getMessage());
        }

    }
}
