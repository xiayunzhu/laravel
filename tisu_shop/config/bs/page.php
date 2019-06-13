<?php
/**
 * Created by PhpStorm.
 * User: JJG
 * Date: 2019/4/29
 * Time: 12:07
 */
return [
    'home' => [
        [
            'title' => '今日主推',
            'type' => 'todayMainPush',
            'description' => '今日主推',
            'introduction' => '今日主推',
            'sort' => 1,
            'list' => [
                ['title' => '商品1', 'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/29/ZpHY1RdZwcodpVZfdtfcHUZ7oUx0XW7WnSGf6PMe.png', 'org_goods_id' => 1],
                ['title' => '商品2', 'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/29/F4ejDDUS5X1KIGinfN7pqNkvLHCGuLKCbkgb6gp0.png', 'org_goods_id' => 2],
                ['title' => '商品3', 'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/29/DN5rDmw3Bp64pE7Q1GY1FYf8HbUqkt3RM6ufxsP1.png', 'org_goods_id' => 3],
            ],
        ],
        [
            'title' => '分类导航',
            'type' => 'navTab',
            'description' => '今日主推',
            'introduction' => '今日主推',
            'sort' => 2,
            'list' => [
                ['title' => '新品', 'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/29/UtmVII5THXUZxiTMifEs5tllRrGIijCeDiIvGsJ8.png', 'link' => ''],
                ['title' => '返佣', 'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/29/uKnr5LK5I5NHg9tUl4DRMIHW8OBdIW1m7aDcc93U.png', 'link' => ''],
                ['title' => '销量', 'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/29/QOgl9lIhWREcGjVWuCMipNBXjmCJUxOWDJxOehJi.png', 'link' => ''],
                ['title' => '套餐', 'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/29/LhuOZuaq7Qcowht4FPDRzdrvEN8E70ojTOeS1Jwi.png', 'link' => ''],
            ]
        ],
//        [
//            'title' => '报名活动',
//            'type' => 'activity',
//            'sort' => 3,
//        ],
        [
            'title' => '新品速递',
            'type' => 'upperModule',
            'description' => '新品抢先上架',
            'introduction' => '新品抢先上架',
            'sort' => 4,
            'bg_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/29/L6iIk4xmlVNTLmTHZiJJyXAUUSh9u4EEbAiHwQcp.png',
            'show_style' => 'gt_three',//gt_three:3个商品以上,eq_three:3个商品,eq_two:2个商品,
            'list' => [
                [
                    'title' => '自颜源露舒缓清透面膜 绿色 低价',
                    'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/26/2g7hlY30bS2JEyifrDPHGYYUbgcIjUcmTzo9LUdz.jpeg',
                    'org_goods_id' => 1,
                    'price' => 159,
                    'commission' => 120,
                    'is_new' => 'Y',
                ],
                [
                    'title' => '自颜源露舒缓清透面膜 绿色 低价2',
                    'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/26/2g7hlY30bS2JEyifrDPHGYYUbgcIjUcmTzo9LUdz.jpeg',
                    'org_goods_id' => 1,
                    'price' => 159,
                    'commission' => 120,
                    'is_new' => 'Y',
                ],
                [
                    'title' => '自颜源露舒缓清透面膜 绿色 低价3',
                    'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/26/2g7hlY30bS2JEyifrDPHGYYUbgcIjUcmTzo9LUdz.jpeg',
                    'org_goods_id' => 1,
                    'price' => 159,
                    'commission' => 120,
                    'is_new' => 'Y',
                ],
                [
                    'title' => '自颜源露舒缓清透面膜 绿色 低价4',
                    'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/26/2g7hlY30bS2JEyifrDPHGYYUbgcIjUcmTzo9LUdz.jpeg',
                    'org_goods_id' => 1,
                    'price' => 159,
                    'commission' => 120,
                    'is_new' => 'Y',
                ],
                [
                    'title' => '自颜源露舒缓清透面膜 绿色 低价5',
                    'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/26/2g7hlY30bS2JEyifrDPHGYYUbgcIjUcmTzo9LUdz.jpeg',
                    'org_goods_id' => 1,
                    'price' => 159,
                    'commission' => 120,
                    'is_new' => 'Y',
                ],
                [
                    'title' => '自颜源露舒缓清透面膜 绿色 低价6',
                    'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/26/2g7hlY30bS2JEyifrDPHGYYUbgcIjUcmTzo9LUdz.jpeg',
                    'org_goods_id' => 1,
                    'price' => 159,
                    'commission' => 120,
                    'is_new' => 'Y',
                ],
            ]
        ],
        [
            'title' => '佣金赚不停',//高佣金模块
            'type' => 'upperModule',
            'description' => '超多佣金等你来赚',
            'introduction' => '超多佣金等你来赚',
            'sort' => 5,
            'show_style' => 'eq_two',//2个商品
            'list' => [
                [
                    'title' => '自颜源露舒缓清透面膜 绿色 低价',
                    'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/26/2g7hlY30bS2JEyifrDPHGYYUbgcIjUcmTzo9LUdz.jpeg',
                    'org_goods_id' => 1,
                    'price' => 159,
                    'commission' => 120,
                    'is_new' => 'Y',
                ],
                [
                    'title' => '自颜源露舒缓清透面膜 绿色 低价2',
                    'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/26/2g7hlY30bS2JEyifrDPHGYYUbgcIjUcmTzo9LUdz.jpeg',
                    'org_goods_id' => 1,
                    'price' => 159,
                    'commission' => 120,
                    'is_new' => 'Y',
                ],
            ]
        ],
        [
            'title' => '精品套餐',
            'type' => 'upperModule',
            'description' => '新品套餐一件打包上架',
            'introduction' => '新品套餐一件打包上架',
            'sort' => 6,
            'bg_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/29/yqJEbSNWz2G7jOWEdhHhW2tmZLlcfL4mcI9fH5nj.png',
            'show_style' => 'eq_two',//2个商品
            'list' => [
                [
                    'title' => '自颜源露舒缓清透面膜 绿色 低价',
                    'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/26/2g7hlY30bS2JEyifrDPHGYYUbgcIjUcmTzo9LUdz.jpeg',
                    'org_goods_id' => 1,
                    'price' => 159,
                    'commission' => 120,
                    'is_new' => 'Y',
                ],
                [
                    'title' => '自颜源露舒缓清透面膜 绿色 低价2',
                    'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/26/2g7hlY30bS2JEyifrDPHGYYUbgcIjUcmTzo9LUdz.jpeg',
                    'org_goods_id' => 1,
                    'price' => 159,
                    'commission' => 120,
                    'is_new' => 'Y',
                ],
            ]
        ],
        [
            'title' => '爆款热卖',
            'type' => 'upperModule',
            'description' => '每周TOP销量排行榜',
            'introduction' => '每周TOP销量排行榜',
            'sort' => 7,
            'show_style' => 'top_four',//top4
            'list' => [
                [
                    'title' => '自颜源露舒缓清透面膜 绿色 低价',
                    'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/26/2g7hlY30bS2JEyifrDPHGYYUbgcIjUcmTzo9LUdz.jpeg',
                    'org_goods_id' => 1,
                    'price' => 159,
                    'commission' => 120,
                    'is_new' => 'Y',
                ],
                [
                    'title' => '自颜源露舒缓清透面膜 绿色 低价2',
                    'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/26/2g7hlY30bS2JEyifrDPHGYYUbgcIjUcmTzo9LUdz.jpeg',
                    'org_goods_id' => 1,
                    'price' => 159,
                    'commission' => 120,
                    'is_new' => 'Y',
                ],
                [
                    'title' => '自颜源露舒缓清透面膜 绿色 低价3',
                    'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/26/2g7hlY30bS2JEyifrDPHGYYUbgcIjUcmTzo9LUdz.jpeg',
                    'org_goods_id' => 1,
                    'price' => 159,
                    'commission' => 120,
                    'is_new' => 'Y',
                ],
                [
                    'title' => '自颜源露舒缓清透面膜 绿色 低价4',
                    'image_url' => 'http://tisu.oss-cn-hangzhou.aliyuncs.com/images/pics/201904/26/2g7hlY30bS2JEyifrDPHGYYUbgcIjUcmTzo9LUdz.jpeg',
                    'org_goods_id' => 1,
                    'price' => 159,
                    'commission' => 120,
                    'is_new' => 'Y',
                ],
            ]
        ],


    ],
];