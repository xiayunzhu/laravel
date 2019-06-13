#!/usr/bin/env bash
## 代码拉取
git pull

## 重启 swoole http 服务
php artisan swoole:http restart