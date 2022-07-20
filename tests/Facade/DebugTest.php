<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/13
 * Time: 15:50
 */

namespace Mrstock\Mjc\Test\Facade;


use Mrstock\Mjc\Facade\Debug;
use PHPUnit\Framework\TestCase;

class DebugTest extends TestCase
{
    public function testGetFacadeClass()
    {
        $app = new Debug();
        //断言为object
        $this->assertIsObject($app);
        //断言为空
        $this->assertObjectNotHasAttribute('debug', $app);
    }
}