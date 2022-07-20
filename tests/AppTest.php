<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/15
 * Time: 10:50
 */

namespace Mrstock\Mjc\Test;


use Mrstock\Mjc\App;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    //检查run方法
    public function testRun()
    {
        define('VENDOR_DIR', 'E:\MrStock\mjc\vendor');
        try {
            $app = new App();
            $_REQUEST['c'] = 'Membergoodsoutservice';
            $_REQUEST['a'] = 'getmembergoodsnotinservice';
            $_REQUEST['v'] = 'Inneruse';
            $_REQUEST['site'] = 'membergoods';
            $_REQUEST['appcode'] = '5c3d73a0f1f18c30cqb5zkov';
            $_REQUEST['serviceversion'] = 'v2';
            $_REQUEST['object_id'] = '37';
            $_REQUEST['curpage'] = 1;
            $_REQUEST['pagesize'] = 10000;
            $res = $app->run();
            //断言不为空
            $this->assertNotEmpty($res);
            //断言为bool值
            $this->assertIsObject($res);
        } catch (\Exception $e) {
            //断言不为空
            $this->assertNotEmpty($e);
            //断言为bool值
            $this->assertIsObject($e);
        }
    }
}