<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/14
 * Time: 16:53
 */

namespace Mrstock\Mjc\Test\Log;


use Mrstock\Mjc\Log\LogLevel;
use PHPUnit\Framework\TestCase;

class LogLevelTest extends TestCase
{
    //检查系统错误
    public function testErr()
    {
        $LogLevel = new LogLevel();

        $res = $LogLevel::ERR;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'ERR');
    }

    //检查系统异常
    public function testExp()
    {
        $LogLevel = new LogLevel();

        $res = $LogLevel::EXP;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'EXP');
    }

    //检查系统提示或者警告日志
    public function testNotice()
    {
        $LogLevel = new LogLevel();

        $res = $LogLevel::NOTICE;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'NOTICE');
    }

    //检查数据库操作错误
    public function testDberr()
    {
        $LogLevel = new LogLevel();

        $res = $LogLevel::DBERR;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'DBERR');
    }

    //检查数据库慢查询日志
    public function testDbslow()
    {
        $LogLevel = new LogLevel();

        $res = $LogLevel::DBSLOW;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'DBSLOW');
    }

    //检查数据库事务回滚日志
    public function testDbrollback()
    {
        $LogLevel = new LogLevel();

        $res = $LogLevel::DBROLLBACK;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'DBROLLBACK');
    }

    //检查redis 错误
    public function testRediserr()
    {
        $LogLevel = new LogLevel();

        $res = $LogLevel::REDISERR;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'REDISERR');
    }

    //检查访问日志不带结果
    public function testAccess()
    {
        $LogLevel = new LogLevel();

        $res = $LogLevel::ACCESS;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'ACCESS');
    }

    //检查业务层异常
    public function testRouteerr()
    {
        $LogLevel = new LogLevel();

        $res = $LogLevel::ROUTEERR;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'ROUTEERR');
    }

    //检查路由错误 找不到控制器
    public function testRoutenone()
    {
        $LogLevel = new LogLevel();

        $res = $LogLevel::ROUTENONE;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'ROUTENONE');
    }

    //检查curl 错误日志
    public function testCurlerr()
    {
        $LogLevel = new LogLevel();

        $res = $LogLevel::CURLERR;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'CURLERR');
    }

    //检查mq 错误日志
    public function testMqerr()
    {
        $LogLevel = new LogLevel();

        $res = $LogLevel::MQERR;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'MQERR');
    }

    //检查rpc 错误日志
    public function testRpcerr()
    {
        $LogLevel = new LogLevel();

        $res = $LogLevel::RPCERR;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'RPCERR');
    }

    //检查访问记录 记录返回结果
    public function testRouterecord()
    {
        $LogLevel = new LogLevel();

        $res = $LogLevel::ROUTERECORD;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'ROUTERECORD');
    }
}