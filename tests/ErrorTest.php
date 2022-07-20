<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/15
 * Time: 18:07
 */

namespace Mrstock\Mjc\Test;


use Mrstock\Mjc\Error;
use Mrstock\Mjc\Exception\ErrorException;
use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{
    //检查注册异常处理 + 该托管任务会截断流程
    public function testRegister()
    {
        $error = new Error();

        $error->register();

        $this->assertEquals(1, 1);
    }

    //检查AppError
    public function testAppError()
    {
        $error = new Error();

        $error->appError(E_NOTICE, 1, 1, 1);

        $this->assertEquals(E_NOTICE, 8);
    }

    //检查appException
    public function testAppException()
    {
        try {
            define('VENDOR_DIR', 'E:\MrStock\mjc\vendor');
            $error = new Error();

            $exception = new ErrorException('21212121', 500, 500, 'test\log', time());
            $error->appException($exception);
        } catch (\Exception $e) {
            //断言message
            $this->assertEquals($e->getMessage(), 'ok');
            //断言code
            $this->assertEquals($e->getCode(), 0);
        }

    }

    //检查appShutdown
    public function testAppShutdown()
    {
        $error = new Error();

        $error->appShutdown();

        $this->assertEquals(1, 1);
    }
}