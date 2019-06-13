<?php
/**
 * Created by PhpStorm.
 * User: ML-05
 * Date: 2019/3/30
 * Time: 18:06
 */

use Faker\Generator as Faker;

$factory->define(\App\Models\PageContent::class, function (Faker $faker) {

    $title = [
        "新品试色 |娇韵诗唇膏737 |spicy cinnamon",
        "FILORGA菲洛嘉新肌赋活面霜",
        "FILORGA菲洛嘉新肌赋活乳霜",
    ];
    $describe = [
        "CLARINS娇韵诗出彩妆辣！这颗介于小辣椒和奶茶色中间的微妙仙子我先入为敬！",
        "菲洛嘉的第一款也是目前为止唯一的一款肌底精华液，略带粘稠感的液体，延展性很好，一点就可以涂满整脸，吸收也超迅速。保湿力棒棒的，能帮助后续产品的吸收。使用完之后你就能立刻感觉到什么叫“自带高光”！",
        "这个是当之无愧的爆款面霜啊！把一整支NCTF都放进了一瓶面霜里，晚上厚厚的涂上一层，早上起来脸自然能发光啊！"];
    return [
        "shop_id" => 1,
        "image_url" => $faker->imageUrl(750, 500),
        "title" => $title[rand(0, 2)],
        "describe" => $describe[rand(0, 2)],
        "type" => "styleOne",
    ];
});