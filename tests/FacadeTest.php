<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/16
 * Time: 10:14
 */

namespace Mrstock\Mjc\Test;


use Mrstock\Mjc\App;
use Mrstock\Mjc\Facade;
use PHPUnit\Framework\TestCase;

class FacadeTest extends TestCase
{
    //检查 绑定类的静态代理
    public function testBind()
    {
        if (!defined('APP_PATH')) {
            define('APP_PATH', __DIR__ . '/../src');
        }
        if (!defined('VENDOR_DIR')) {
            define('VENDOR_DIR', __DIR__ . '/../vendor');
        }
        $facade = new Facade();

        $res = $facade->bind('app', App::class);

        //断言不为空
        $this->assertEmpty($res);
        //断言为null
        $this->assertNull($res);
    }

    //检查 带参数实例化当前Facade类
    public function testInstance()
    {
        $facade = new Facade();

        $res = $facade->instance();
        //断言不为空
        $this->assertEmpty($res);
        //断言为
        $this->assertEquals($res, 0);
        //断言为null
        $this->assertNull($res);
    }

    //检查调用类的实例
    public function testMake()
    {
        $facade = new Facade();

        $res = $facade->make('app');

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
        //断言值
        $this->assertObjectHasAttribute('bind', $res);
    }

    //检查调用实际类的方法
    public function test__callStatic()
    {
        $facade = new Facade();

        $res = $facade->__callStatic('make', ['app']);

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
        //断言值
        $this->assertObjectHasAttribute('bind', $res);
    }
}