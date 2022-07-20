<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/15
 * Time: 18:27
 */

namespace Mrstock\Mjc\Test;


use Mrstock\Mjc\Errorcli;
use Mrstock\Mjc\Exception\ErrorException;
use PHPUnit\Framework\TestCase;

class ErrorcliTest extends TestCase
{
    //检查注册异常处理 + 该托管任务会截断流程
    public function testRegister()
    {
        if (!defined('APP_PATH')) {
            define('APP_PATH', __DIR__ . '/../src');
        }
        $error = new Errorcli();

        $error->register();

        $this->assertEquals(1, 1);
    }

    //检查cliError
    public function testCliError()
    {
        try {
            $error = new Errorcli();

            $time = time();
            $res = $error->cliError(202, '呵呵哒', '\date\logs', $time);
            //断言不为空
            $this->assertNotEmpty($res);
            //断言为数组
            $this->assertIsArray($res);
            //断言errno值
            $this->assertEquals($res['errno'], 202);
            //断言message值
            $this->assertEquals($res['message'], '呵呵哒');
            //断言file值
            $this->assertEquals($res['file'], '\date\logs');
            //断言line值
            $this->assertEquals($res['line'], $time);
        } catch (\Exception $e) {
            //断言不为空
            $this->assertNotEmpty($e);
            //断言为对象
            $this->assertIsObject($e);
        }
    }

    //断言 自定义异常处理(中断流程)
    public function testCliException()
    {
        try {
            $error = new Errorcli();

            $exception = new ErrorException('21212121', -500, 500, 'test\log', time());
            $error->cliException($exception);
            //断言message
            $this->assertEquals($exception->getMessage(), 21212121);
            //断言code
            $this->assertEquals($exception->getCode(), -500);
        } catch (\Exception $e) {
            //断言不为空
            $this->assertNotEmpty($e);
            //断言为对象
            $this->assertIsObject($e);
        }
    }

    //断言 致命错误捕获(中断流程)
    public function testCliShutdown()
    {
        try {

            $error = new Errorcli();

            $error->cliShutdown();

            $this->assertEquals(1, 1);
        } catch (\Exception $e) {
            //断言不为空
            $this->assertNotEmpty($e);
            //断言为对象
            $this->assertIsObject($e);
        }
    }
}