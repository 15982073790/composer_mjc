<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/13
 * Time: 15:51
 */

namespace Mrstock\Mjc\Test\Facade;


use Mrstock\Mjc\Facade\Hook;
use PHPUnit\Framework\TestCase;

class HookTest extends TestCase
{
    public function testGetFacadeClass()
    {
        $app = new Hook();
        //断言为object
        $this->assertIsObject($app);
        //断言为空
        $this->assertObjectNotHasAttribute('hook', $app);
    }
}