<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/13
 * Time: 15:56
 */

namespace Mrstock\Mjc\Test\Facade;


use Mrstock\Mjc\Facade\Middleware;
use PHPUnit\Framework\TestCase;

class MiddlewareTest extends TestCase
{
    public function testGetFacadeClass()
    {
        $app = new Middleware();
        //断言为object
        $this->assertIsObject($app);
        //断言为空
        $this->assertObjectNotHasAttribute('middleware', $app);
    }
}