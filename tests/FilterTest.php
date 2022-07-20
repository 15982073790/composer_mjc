<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/16
 * Time: 10:48
 */

namespace Mrstock\Mjc\Test;


use Mrstock\Mjc\Filter;
use Mrstock\Mjc\Http\Request;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    public function testHandle()
    {
        $filter = new Filter();
        $request = new Request();
        $request->param['id'] = 1;
        $request->param['name'] = '呵呵哒';
        try {
            $res = $filter->handle($request, function () {
                return 1;
            });
            //断言不为空
            $this->assertNotEmpty($res);
            //断言为int
            $this->assertIsInt($res);
            //断言值
            $this->assertEquals($res, 1);
        } catch (\Exception $e) {
            //断言不为空
            $this->assertNotEmpty($e);
            //断言为对象
            $this->assertIsObject($e);
            //断言值
            $this->assertNotEmpty($e->getMessage());
            //断言值
            $this->assertIsString($e->getMessage());
        }


    }
}