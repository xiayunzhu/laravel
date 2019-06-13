<?php


namespace App\Handlers;

use App\Models\Message;

class MessageHandler
{

    /**
     * 新增消息
     * @param array $message
     * @return mixed
     */
    public function store(array $message)
    {
        $model = Message::create($message);

        return $model;
    }

}