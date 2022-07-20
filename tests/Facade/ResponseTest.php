<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/13
 * Time: 15:58
 */

namespace Mrstock\Mjc\Test\Facade;


use Mrstock\Mjc\Facade\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testGetFacadeClass()
    {
        $app = new Response();
        //断言为object
        $this->assertIsObject($app);
        //断言为空
        $this->assertObjectNotHasAttribute('response', $app);
    }
}