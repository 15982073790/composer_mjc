<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/14
 * Time: 10:30
 */

namespace Mrstock\Mjc\Test\Http;


use Mrstock\Mjc\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    //检查架构函数
    public function testRequestInit()
    {
        $_rdata['c'] = 'Membergoods';
        $_rdata['a'] = 'gettime';
        $_rdata['v'] = 'app';
        $_rdata['site'] = 'membergoods';
        $_rdata['appcode'] = '5c3d73a0f1f18c30cqb5zkov';
        $_rdata['rpc_msg_id'] = '10427eb39c335fb7e6f12e9e9f8106cf';
        $_rdata['rpc_msg_id'] = '2020-07-14 13:25:43.111';

        //断言不为空
        $this->assertNotEmpty($_rdata);
        $_REQUEST = $_rdata;

        $_sdata['REMOTE_ADDR'] = '127.0.0.1';
        $_sdata['SERVER_PORT'] = 80;
        $_sdata['SERVER_ADDR'] = 80;
        $_sdata['SERVER_NAME'] = 'dexun.membergoods';
        //断言不为空
        $this->assertNotEmpty($_rdata);
        $_SERVER = $_sdata;

        $Request = new Request();
        $res = $Request->init();

        $this->assertEmpty($res);
    }

    //检查设置请求数据
    public function testSet()
    {
        $Request = new Request();

        $res = $Request->__set('serviceservice', 'v2');
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值为v2
        $this->assertEquals($res, 'v2');
    }

    public function testGet()
    {
        $Request = new Request();
        $set_res = $Request->__set('serviceservice', 'v2');
        //断言不为空
        $this->assertNotEmpty($set_res);
        //断言为字符串
        $this->assertIsString($set_res);
        //断言值为v2
        $this->assertEquals($set_res, 'v2');

        $get_res = $Request->__get('serviceservice');

        //断言不为空
        $this->assertNotEmpty($get_res);
        //断言为字符串
        $this->assertIsString($get_res);
        //断言值为v2
        $this->assertEquals($get_res, 'v2');
    }

    //检查判断属性是否空
    public function testIsset()
    {
        $Request = new Request();
        $set_res = $Request->__set('serviceservice', 'v2');
        //断言不为空
        $this->assertNotEmpty($set_res);
        //断言为字符串
        $this->assertIsString($set_res);
        //断言值为v2
        $this->assertEquals($set_res, 'v2');

        $isset_res = $Request->__isset('serviceservice');
        //断言不为空
        $this->assertNotEmpty($isset_res);
        //断言值为v2
        $this->assertEquals($isset_res, 1);
    }

    //检查offsetSet设置
    public function testOffsetSet()
    {
        $Request = new Request();
        $set_res = $Request->offsetSet('serviceservice', 'v2');

        //断言不为空
        $this->assertNotEmpty($set_res);
        //断言为字符串
        $this->assertIsString($set_res);
        //断言值为v2
        $this->assertEquals($set_res, 'v2');
    }

    //检查offsetGet设置
    public function testOffsetGet()
    {
        $Request = new Request();
        $set_res = $Request->offsetSet('serviceservice', '1212');

        //断言不为空
        $this->assertNotEmpty($set_res);
        //断言为字符串
        $this->assertIsString($set_res);
        //断言值为v2
        $this->assertEquals($set_res, '1212');

        $get_res = $Request->offsetGet('serviceservice');

        //断言不为空
        $this->assertNotEmpty($get_res);
        //断言为字符串
        $this->assertIsString($get_res);
        //断言值为v2
        $this->assertEquals($get_res, '1212');
    }

    public function testOffsetExists()
    {
        $Request = new Request();
        $set_res = $Request->offsetSet('serviceservice', 'v2');
        //断言不为空
        $this->assertNotEmpty($set_res);
        //断言为字符串
        $this->assertIsString($set_res);
        //断言值为v2
        $this->assertEquals($set_res, 'v2');
        //判断值是否存在
        $get_res = $Request->offsetExists('serviceservice');

        //断言不为空
        $this->assertNotEmpty($get_res);
        //断言值为v2
        $this->assertEquals($get_res, 1);
    }

    public function testOffsetUnset()
    {
        $Request = new Request();
        //设置值
        $set_res = $Request->offsetSet('serviceservice', 'v2');

        //断言不为空
        $this->assertNotEmpty($set_res);
        //断言为字符串
        $this->assertIsString($set_res);
        //断言值为v2
        $this->assertEquals($set_res, 'v2');
        //清除值
        $Request->offsetUnset('serviceservice');
        //判断值是否存在
        $get_res = $Request->offsetExists('serviceservice');
        //断言不为空
        $this->assertEmpty($get_res);
        //断言值为v2
        $this->assertEquals($get_res, 0);
    }
}