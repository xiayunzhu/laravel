#!/usr/bin/env bash

#处理队列

php artisan queue:work --queue=returnStock,loginEventListener,closeOrder,wxPayReport,GoodsInfoChange