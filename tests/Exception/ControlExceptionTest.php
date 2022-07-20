<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/13
 * Time: 14:00
 */

namespace Mrstock\Mjc\Test\Exception;


use Mrstock\Mjc\Exception\ControlException;
use PHPUnit\Framework\TestCase;

class ControlExceptionTest extends TestCase
{
    //检查 获取错误级别
    public function testGetSeverity()
    {
        $ControlException = new ControlException('错误信息', -1);

        $res = $ControlException->getSeverity();
        //断言为空
        $this->assertEmpty($res);
    }

    //检查 状态值
    public function testGetStatus()
    {
        $ControlException = new ControlException('错误信息', -1);

        $res = $ControlException->getStatus();
        //断言为空
        $this->assertNotEmpty($res);
        //断言值INT
        $this->assertIsInt($res);
        //断言值
        $this->assertEquals($res, 200);

    }
}