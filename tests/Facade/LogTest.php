<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/13
 * Time: 15:54
 */

namespace Mrstock\Mjc\Test\Facade;


use Mrstock\Mjc\Facade\Log;
use PHPUnit\Framework\TestCase;

class LogTest extends TestCase
{
    public function testGetFacadeClass()
    {
        $app = new Log();
        //断言为object
        $this->assertIsObject($app);
        //断言为空
        $this->assertObjectNotHasAttribute('log', $app);
    }
}