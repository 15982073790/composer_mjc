<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/13
 * Time: 14:23
 */

namespace Mrstock\Mjc\Test\Exception;

use PHPUnit\Framework\TestCase;

class HttpResponseExceptionTest extends TestCase
{
    public function testGetResponse()
    {
        $res = 'dkfmsdfkmdslkfmkdsfmkds';
        //断言不能为空
        $this->assertNotEmpty($res);
        //断言为object
        //$this->assertIsObject($res);
    }
}