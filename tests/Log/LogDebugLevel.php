<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/14
 * Time: 16:37
 */

namespace Mrstock\Mjc\Test\Log;


use PHPUnit\Framework\TestCase;

class LogDebugLevel extends TestCase
{

    //检查调试日志
    public function testDebug()
    {
        $LogDebugLevel = new \Mrstock\Mjc\Log\LogDebugLevel();

        $res = $LogDebugLevel::DEBUG;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'DEBUG');
    }

    //检查数据操作类专门记录sql数据使用
    public function testSqlrecord()
    {
        $LogDebugLevel = new \Mrstock\Mjc\Log\LogDebugLevel();

        $res = $LogDebugLevel::SQLRECORD;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'SQLRECORD');
    }

    //检查REDIS 查询日志
    public function testRedisrecord()
    {
        $LogDebugLevel = new \Mrstock\Mjc\Log\LogDebugLevel();

        $res = $LogDebugLevel::REDISRECORD;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'REDISRECORD');
    }

    //检查文件访问日志
    public function testFileRecord()
    {
        $LogDebugLevel = new \Mrstock\Mjc\Log\LogDebugLevel();

        $res = $LogDebugLevel::FILERECORD;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'FILERECORD');
    }

    //检查rpc 调式
    public function testRpcdebug()
    {
        $LogDebugLevel = new \Mrstock\Mjc\Log\LogDebugLevel();

        $res = $LogDebugLevel::RPCDEBUG;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'RPCDEBUG');
    }

    //检查cli echo
    public function testCliecho()
    {
        $LogDebugLevel = new \Mrstock\Mjc\Log\LogDebugLevel();

        $res = $LogDebugLevel::CLIECHO;
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsString($res);
        //断言值
        $this->assertEquals($res, 'CLIECHO');
    }

}