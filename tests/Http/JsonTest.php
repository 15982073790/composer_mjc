<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/13
 * Time: 16:00
 */

namespace Mrstock\Mjc\Test\Http;

use Mrstock\Mjc\Http\Json;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    //断言content_type值
    public function testContentType()
    {
        $content_type = 'text/plain';
        //断言不为空
        $this->assertNotEmpty($content_type);
        //断言值
        $this->assertEquals($content_type, 'text/plain');
    }

    //断言OutPutData传参
    public function testOutPutData()
    {
        $data['id'] = 1;
        $data['name'] = '呵呵哒';

        //断言不为空
        $this->assertNotEmpty($data);
        //断言为数组
        $this->assertIsArray($data);
    }
}