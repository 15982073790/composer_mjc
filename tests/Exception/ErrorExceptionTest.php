<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/13
 * Time: 14:20
 */

namespace Mrstock\Mjc\Test\Exception;


use Mrstock\Mjc\Exception\ErrorException;
use PHPUnit\Framework\TestCase;

class ErrorExceptionTest extends TestCase
{
    //检查 状态值
    public function testGetStatus()
    {
        $ErrorException = new ErrorException('错误信息', -1);

        $res = $ErrorException->getStatus();

        //断言为空
        $this->assertNotEmpty($res);
        //断言值INT
        $this->assertIsInt($res);
        //断言值
        $this->assertEquals($res, 500);

    }
}