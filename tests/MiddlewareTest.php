<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/16
 * Time: 14:44
 */

namespace Mrstock\Mjc\Test;


use Mrstock\Mjc\App;
use Mrstock\Mjc\Http\Request;
use Mrstock\Mjc\Middleware;
use PHPUnit\Framework\TestCase;

class MiddlewareTest extends TestCase
{

    //检查批量导入插件
    public function testImport()
    {
        if (!defined('VENDOR_DIR')) {
            define('VENDOR_DIR', 'E:\MrStock\mjc\vendor');
        }
        $app = new App();
        $hook = new Middleware($app);

        $res = $hook->import([]);

        //断言不为空
        $this->assertEmpty($res);
        //断言为对象
        $this->assertNull($res);
    }

    //检查动态添加行为扩展到某个标签
    public function testAdd()
    {
        $app = new App();
        $hook = new Middleware($app);
        //检查
        $res = $hook->add(null);
        //断言不为空
        $this->assertEmpty($res);
        //断言为对象
        $this->assertNull($res);
    }

    //检查注册控制器中间件
    public function testController()
    {
        $app = new App();
        $hook = new Middleware($app);
        //检查
        $res = $hook->controller(null);

        //断言不为空
        $this->assertEmpty($res);
        //断言为对象
        $this->assertNull($res);
    }

    //检查移除中间件
    public function testUnshift()
    {
        $app = new App();
        $hook = new Middleware($app);
        //检查
        $res = $hook->unshift(null);

        //断言不为空
        $this->assertEmpty($res);
        //断言为对象
        $this->assertNull($res);
    }

    //检查获取注册的中间件
    public function testAll()
    {
        $app = new App();
        $hook = new Middleware($app);
        //检查
        $res = $hook->all();
        //断言不为空
        $this->assertEmpty($res);
        //断言为对象
        $this->assertIsArray($res);
    }

    //检查清除中间件
    public function testClear()
    {
        $app = new App();
        $hook = new Middleware($app);
        //检查
        $res = $hook->clear();
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsBool($res);
        //断言为对象
        $this->assertEquals($res, 1);
    }

    //检查中间件调度
    public function testDispatch()
    {
        try {
            $app = new App();
            $hook = new Middleware($app);
            $request = new Request();
            //检查
            $res = $hook->dispatch($request);

            //断言为空
            $this->assertEmpty($res);
            //断言为对象
            $this->assertIsBool($res);
            //断言为对象
            $this->assertEquals($res, false);
        } catch (\Exception $e) {

            //断言不为空
            $this->assertNotEmpty($e);
            //断言为对象
            $this->assertIsObject($e);
            //断言为对象
            $this->assertEquals($e->getMessage(), 'The queue was exhausted, with no response returned');
        }

    }

    //检查__debugInfo
    public function test__debugInfo()
    {
        $app = new App();
        $hook = new Middleware($app);

        //检查
        $res = $hook->__debugInfo();
        //断言为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsArray($res);
        //断言为对象
        $this->assertEquals($res['queue'], []);
    }
}