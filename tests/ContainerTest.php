<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/15
 * Time: 11:10
 */

namespace Mrstock\Mjc\Test;


use Mrstock\Mjc\App;
use Mrstock\Mjc\Container;
use Mrstock\Mjc\Debug;
use Mrstock\Mjc\Route\AC;
use PHPUnit\Framework\TestCase;
use Reflector;

class ContainerTest extends TestCase
{
    //检查获取当前容器的实例（单例）
    public function testGetInstance()
    {
        $Container = new Container();

        $res = $Container->getInstance();
        //断言为空
        $this->assertObjectHasAttribute('bind', $res);
        //断言为对象
        $this->assertIsObject($res);
    }

    //检查设置当前容器的实例
    public function testSetInstance()
    {
        $Container = new Container();
        //设置
        $Container->setInstance(Hook::class);
        //获取
        $res = $Container->getInstance();

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'Mrstock\Mjc\Test\Hook');
    }

    //检查绑定一个类、闭包、实例、接口实现到容器
    public function testSet()
    {
        $Container = new Container();
        //设置
        $Container->setInstance($Container);

        $res = $Container->set('Errorcli', 'Mrstock\Mjc\Errorcli');

        //断言为对象
        $this->assertIsObject($res);
    }

    //检查获取容器中的对象实例
    public function testGet()
    {
        define('VENDOR_DIR', 'E:\MrStock\mjc\vendor');
        $Container = new Container();

        //设置
        $Container->setInstance($Container);
        $res = $Container->set('Errorcli', 'Mrstock\Mjc\Errorcli');
        //断言为对象
        $this->assertIsObject($res);

        $get_res = $Container->get('app');

        //断言不为空
        $this->assertNotEmpty($get_res);
        //断言为对象
        $this->assertIsObject($get_res);
    }

    //检查移除容器中的对象实例
    public function testRemove()
    {
        $Container = new Container();
        //设置
        $Container->setInstance($Container);
        $res = $Container->set('Errorcli', 'Mrstock\Mjc\Errorcli');
        //断言为对象
        $this->assertIsObject($res);
        //移除
        $Container->remove('Errorcli');
        //获取参数
        $get_res = $Container->get('Errorcli');
        //断言不为空
        $this->assertNotEmpty($get_res);
        //断言为对象
        $this->assertIsObject($get_res);
    }

    //检查清除容器中的对象实例
    public function testClear()
    {
        $Container = new Container();
        //设置
        $Container->setInstance($Container);
        //移除
        $Container->clear();
        //获取参数
        $get_res = $Container->get('app');

        //断言不为空
        $this->assertNotEmpty($get_res);
        //断言为对象
        $this->assertIsObject($get_res);
    }

    //检查绑定一个类、闭包、实例、接口实现到容器
    public function testBindTo()
    {
        $Container = new Container();

        $res = $Container->bindTo('Errorcli', 'Mrstock\Mjc\Errorcli');

        //断言为对象
        $this->assertIsObject($res);
        //断言对象包含的参数
        $this->assertObjectHasAttribute('bind', $Container);
    }

    //检查绑定一个类实例当容器
    public function testInstance()
    {
        $Container = new Container();

        $res = $Container->instance('errorcli', 'Mrstock\Mjc\Errorcli');

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
        //断言对象包含的参数
        $this->assertObjectHasAttribute('bind', $Container);
    }

    //检查判断容器中是否存在类及标识
    public function testBound()
    {
        $Container = new Container();

        $res = $Container->bound('app');
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsBool($res);
        //断言值
        $this->assertEquals($res, 1);
    }

    //检查判断容器中是否存在对象实例
    public function testExists()
    {
        $Container = new Container();

        $Container->instance('app', 'Mrstock\Mjc\App');

        $res = $Container->exists('app');

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsBool($res);
        //断言值
        $this->assertEquals($res, 1);
    }

    //检查判断容器中是否存在类及标识
    public function testHas()
    {
        $Container = new Container();

        $res = $Container->has('app');
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsBool($res);
        //断言值
        $this->assertEquals($res, 1);
    }

    //检查创建类的实例=
    public function testMake()
    {
        $Container = new Container();

        $res = $Container->make('request');

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
    }

    //检查删除容器中的对象实例
    public function testDelete()
    {
        $Container = new Container();

        $Container->delete('bind');

        $res = $Container->has('bind');

        //断言为空
        $this->assertEmpty($res);
        //断言为对象
        $this->assertIsBool($res);
        //断言值
        $this->assertEquals($res, 0);
    }

    //检查获取容器中的对象实例
    public function testAll()
    {
        $Container = new Container();

        $res = $Container->all();

        //断言为空
        $this->assertEmpty($res);
        //断言为对象
        $this->assertIsArray($res);
        //断言值
        $this->assertEquals($res, []);
    }

    //检查清除容器中的对象实例
    public function testflush()
    {
        $Container = new Container();

        $Container->flush();

        $res = $Container->getIterator();

        //断言为空
        $this->assertEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
    }

    //检查执行函数或者闭包方法 支持参数调用
    public function testInvokeFunction()
    {
        $Container = new Container();

        $res = $Container->invokeFunction('flush');

        //断言为空
        $this->assertEmpty($res);
    }

    //检查调用反射执行类的方法 支持参数绑定
    public function testInvokeMethod()
    {
        $Container = new Container();
        $_REQUEST[0] = new Debug(new App());
        $_REQUEST[1] = 'getStep';
        $res = $Container->invokeMethod($_REQUEST);

        //断言为空
        $this->assertEmpty($res);
    }

    //检查调用反射执行类的方法 支持参数绑定
    public function testInvokeReflectMethod()
    {
        $Container = new Container();

        $class = new \ReflectionMethod(\ReflectionMethod::class, '__construct');

        $res = $Container->invokeReflectMethod(\ReflectionMethod::class, $class, ['class_or_method' => '__toString', 'name' => '__toString', 'class' => 'ReflectionMethod']);
        //断言为空
        $this->assertEmpty($res);
    }

    //检查调用反射执行类的实例化 支持依赖注入
    public function testInvokeClass()
    {
        $Container = new Container();
        $res = $Container->invokeClass('Reflection');
        //断言为空
        $this->assertIsObject($res);
    }

    //检查__set
    public function test__set()
    {
        $Container = new Container();
        $data['hehe'] = '212121';
        $res = $Container->__set('app', $data);

        //断言为空
        $this->assertEmpty($res);
    }

    //检查__get
    public function test__get()
    {
        $Container = new Container();

        $res = $Container->__get('app');
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
    }

    //检查__isset
    public function test__isset()
    {
        $Container = new Container();

        $res = $Container->__isset('app');

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsBool($res);
        //断言值
        $this->assertEquals($res, 1);
    }

    //检查__unset
    public function test__unset()
    {
        $Container = new Container();

        $res = $Container->__unset('app');
        //断言不为空
        $this->assertEmpty($res);
    }

    //检查offsetExists
    public function testOffsetExists()
    {
        $Container = new Container();

        $res = $Container->offsetExists('app');
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsBool($res);
        //断言值
        $this->assertEquals($res, 1);
    }

    //检查offsetGet
    public function testOffsetGet()
    {
        $Container = new Container();

        $res = $Container->offsetGet('app');
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
    }

    //检查offsetSet
    public function testOffsetSet()
    {
        $Container = new Container();

        $res = $Container->offsetSet('errorcli', 'Mrstock\Mjc\Errorcli');

        //断言为空
        $this->assertEmpty($res);
        //断言为对象
        $this->assertNull($res);
        //断言值
        $this->assertEquals($res, 0);
    }

    //检查offsetUnset
    public function testOffsetUnset()
    {
        $Container = new Container();

        $res = $Container->offsetUnset('app');
        //断言为空
        $this->assertEmpty($res);
        //断言为对象
        $this->assertNull($res);
        //断言值
        $this->assertEquals($res, null);
    }

    //检查count
    public function testCount()
    {
        $Container = new Container();

        $res = $Container->count();
        //断言为空
        $this->assertEmpty($res);
        //断言为对象
        $this->assertIsInt($res);
        //断言值
        $this->assertEquals($res, 0);
    }

    //检查getIterator
    public function testGetIterator()
    {
        $Container = new Container();

        $res = $Container->getIterator();
        //断言为空
        $this->assertEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
    }

    //检查__debugInfo
    public function test__debugInfo()
    {
        $Container = new Container();

        $res = $Container->__debugInfo();

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsArray($res);
    }


}