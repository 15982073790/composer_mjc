<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/16
 * Time: 13:52
 */

namespace Mrstock\Mjc\Test;


use Mrstock\Mjc\App;
use Mrstock\Mjc\Hook;
use PHPUnit\Framework\TestCase;

class HookTest extends TestCase
{


    //检查指定入口方法名称
    public function testPortal()
    {
        if (!defined('VENDOR_DIR')) {
            define('VENDOR_DIR', 'E:\MrStock\mjc\vendor');
        }

        $app = new App();
        $hook = new Hook($app);

        $res = $hook->portal('run');

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
    }

    //检查指定行为标识 便于调用
    public function testAlias()
    {
        $app = new App();
        $hook = new Hook($app);

        $res = $hook->alias('app', 'Mrstock\Mjc\App');
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
    }

    //检查动态添加行为扩展到某个标签
    public function testAdd()
    {
        $app = new App();
        $hook = new Hook($app);
        //检查
        $hook->add('app', 'Mrstock\Mjc\App');

        $res = $hook->get('app');
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsArray($res);
        //断言值
        $this->assertEquals($res[0], 'Mrstock\Mjc\App');
    }

    //检查批量导入插件
    public function testImport()
    {
        $app = new App();
        $hook = new Hook($app);
        $tags['id'] = 1;
        $tags['name'] = '呵呵哒';
        $tags['sex'] = '男';
        $tags['mobile'] = 13924867231;
        $hook->import($tags);

        $res = $hook->get('mobile');

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsArray($res);
        //断言值
        $this->assertEquals($res[0], 13924867231);
    }

    //检查获取插件信息
    public function testGet()
    {
        $app = new App();
        $hook = new Hook($app);
        $tags['name'] = '呵呵哒';
        $hook->import($tags);

        $res = $hook->get('name');

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsArray($res);
        //断言值
        $this->assertEquals($res[0], '呵呵哒');
    }

    //检查监听标签的行为
    public function testListen()
    {
        $app = new App();
        $hook = new Hook($app);

        $params['id'] = 1;
        $params['name'] = '呵呵哒';
        $params['sex'] = '男';
        $params['mobile'] = 13924867231;

        $tags['app'] = 'app';
        $add['member'] = $tags;

        $hook->import($add);

        $res = $hook->listen('__construct', $params);

        //断言不为空
        $this->assertEmpty($res);
        //断言为数组
        $this->assertIsArray($res);
    }

    //检查执行行为
    public function testExec()
    {
        try {
            $app = new App();
            $hook = new Hook($app);
            $tags['mobile'] = 13924867231;
            $hook->import($tags);
            $res = $hook->exec($app);
            //断言为空
            $this->assertEmpty($res);
            //断言为NULL
            $this->assertNull($res);
        } catch (\Exception $e) {
            //断言不为空
            $this->assertNotEmpty($e);
            //断言为对象
            $this->assertIsObject($e);
        }

    }

    //检查__debugInfo
    public function test__debugInfo()
    {

        $app = new App();
        $hook = new Hook($app);

        $res = $hook->__debugInfo();
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为NULL
        $this->assertIsArray($res);
    }
}