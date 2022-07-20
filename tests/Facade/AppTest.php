<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/13
 * Time: 14:52
 */

namespace Mrstock\Mjc\Test\Facade;


use Mrstock\Mjc\Facade\App;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testGetFacadeClass()
    {
        $app = new App();
        //断言为object
        $this->assertIsObject($app);
        //断言为空
        $this->assertObjectNotHasAttribute('app', $app);
    }
}