<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 16.12.16
 * Time: 14:57
 */

namespace api\modules\home;


class Test
{
    private $m;

    public function testApi(HomeModel $m)
    {
        var_dump($m->getBanners());
    }
}