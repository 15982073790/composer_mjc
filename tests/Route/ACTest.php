<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/14
 * Time: 17:40
 */

namespace Mrstock\Mjc\Test\Route;


use Mrstock\Mjc\App;
use Mrstock\Mjc\Container;
use Mrstock\Mjc\Route\AC;
use PHPUnit\Framework\TestCase;

class ACTest extends TestCase
{
    //检查服务版本
    public function testVersioinparam()
    {
        if (!defined('APP_PATH')) {
            define('APP_PATH', __DIR__ . '/../src');
        }
        $ac = new AC();

        $res = $ac::VERSIOIN_PARAM;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'serviceversion');
    }

    //检查服务版本默认值
    public function testVersioinDefault()
    {
        $ac = new AC();

        $res = $ac::VERSIOIN_DEFAULT;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'application');
    }

    //检查模块参数
    public function testModuleParam()
    {
        $ac = new AC();

        $res = $ac::MODULE_PARAM;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'v');
    }

    //检查默认模块
    public function testModuleDefault()
    {
        $ac = new AC();

        $res = $ac::MODULE_DEFAULT;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'App');
    }

    //检查控制器目录前缀
    public function testControlDir()
    {
        $ac = new AC();

        $res = $ac::CONTROL_DIR;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'Control');
    }

    //控制器参数
    public function testControlParam()
    {
        $ac = new AC();

        $res = $ac::CONTROL_PARAM;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'c');
    }

    //检查控制器后缀
    public function testControlSuffix()
    {
        $ac = new AC();

        $res = $ac::CONTROL_SUFFIX;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'Control');
    }

    //检查操作参数
    public function testActionParam()
    {
        $ac = new AC();

        $res = $ac::ACTION_PARAM;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'a');
    }

    //检查操作后缀
    public function testActionSuffix()
    {
        $ac = new AC();

        $res = $ac::ACTION_SUFFIX;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'Op');
    }

    //检查BefterDispatch
    public function testBefterDispatch()
    {
        try {
            $ac = new AC();
            define('VENDOR_DIR', 'E:\MrStock\mjc\vendor');
            $_REQUEST['c'] = 'index';
            $_REQUEST['a'] = 'index';
            Container::set("app", new App());
            $ac->handle([], function () {
                return 1;
            });
            $res = $ac->befterDispatch();
            //断言空
            $this->assertEmpty($res);
        } catch (\Exception $e) {
            //断言不为空
            $this->assertNotEmpty($e);
            //断言为对象
            $this->assertIsObject($e);
            //断言错误信息包含
            $this->assertIsString($e->getMessage());
            //断言错误信息包含
            $this->assertStringStartsWith('App', $e->getMessage());
        }
    }

    //检查寻找项目内的类检查
    public function testRegister()
    {
        $ac = new AC();

        $res = $ac->register();
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsBool($res);
        //断言值
        $this->assertEquals($res, 1);
    }

    //检查注销寻找项目内的类
    public function testUnRegister()
    {
        $ac = new AC();
        //注册
        $res = $ac->register();
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsBool($res);
        //断言值
        $this->assertEquals($res, 1);
        //注销注册
        $res = $ac->unregister();

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsBool($res);
        //断言值
        $this->assertEquals($res, 1);
    }

    //检查autoload方法
    public function testAutoload()
    {
        $ac = new AC();

        //断言不为空
        $this->assertNotEmpty(APP_PATH);
        //断言值
        $this->assertIsString(APP_PATH);

        $res = $ac->autoload('app');
        //断言不为空
        $this->assertNotEmpty($res);
    }
}