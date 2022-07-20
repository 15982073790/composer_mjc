<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/16
 * Time: 14:34
 */

namespace Mrstock\Mjc\Test;


use Mrstock\Mjc\App;
use Mrstock\Mjc\Log;
use PHPUnit\Framework\TestCase;

class LogTest extends TestCase
{
    //检查record
    public function testRecord()
    {
        $app = new App();
        $log = new Log($app);

        $res = $log->record('测试日志');

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为bool值
        $this->assertIsBool($res);
        //断言值
        $this->assertEquals($res, 1);
    }

    //检查write
    public function testWrite()
    {
        $app = new App();
        $log = new Log($app);

        $res = $log->write('测试日志');

        //断言不为空
        $this->assertEmpty($res);
        //断言为bool值
        $this->assertNull($res);
    }

    //检查记录日志，便于调试 wangsongqing
    public function testWriteLog()
    {
        $app = new App();
        $log = new Log($app);

        $res = $log->writeLog('测试日志');

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为bool值
        $this->assertIsBool($res);
        //断言值
        $this->assertEquals($res, 1);
    }
}