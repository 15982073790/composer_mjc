<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/14
 * Time: 14:41
 */

namespace Mrstock\Mjc\Test\Http;

use Mrstock\Mjc\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    //检查创建Response对象
    public function testCreate()
    {
        $data['id'] = 1;
        $data['name'] = '呵呵哒';

        $Response = new Response($data, 200);

        //请求方法
        $res = $Response->create($data, 'response');

        //断言为对象
        $this->assertIsObject($res);
        //断言不为空
        $this->assertNotEmpty($res);
    }

    //检查输出数据设置
    public function testSend()
    {
        $data['id'] = 1;
        $data['name'] = '呵呵哒';
        $Response = new Response($data, 200);

        $res = $Response->content($data['name']);

        //断言不为空
        $this->assertNotEmpty($res);
        //断言返回值
        $this->assertEquals($res->getContent(), '呵呵哒');

        $Response->send();

    }

    //检查输出的参数
    public function testOptions()
    {
        $data['id'] = 1;
        $data['name'] = '呵呵哒';
        //断言为数组
        $this->assertIsArray($data);
        $Response = new Response($data, 200);

        $data2['id'] = 2;
        $data2['name'] = '嘻嘻嘻';
        //断言为数组
        $this->assertIsArray($data2);

        $res = $Response->options($data2);

        //断言不为空
        $this->assertNotEmpty($res);
    }

    //检查输出数据设置
    public function testData()
    {
        $data['id'] = 1;
        $data['name'] = '呵呵哒';
        //断言为数组
        $this->assertIsArray($data);
        $Response = new Response();

        $res = $Response->data($data);
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
    }

    //检查设置响应头
    public function testHeader()
    {
        $Response = new Response();

        $res = $Response->header('date', 'Tue, 14 Jul 2020 07:58:48 GMT');

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
        //断言值
        $this->assertEquals($res->getHeader()['date'], 'Tue, 14 Jul 2020 07:58:48 GMT');
    }

    //检查设置页面输出内容
    public function testContent()
    {
        $Response = new Response();

        $res = $Response->content('检查设置页面输出内容');
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
        //断言值
        $this->assertEquals($res->getContent(), '检查设置页面输出内容');
    }

    //检查发送HTTP状态
    public function testCode()
    {
        $Response = new Response();

        $res = $Response->code(404);
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
        //断言值
        $this->assertEquals($res->getCode(), 404);
    }

    //检查LastModified
    public function testLastModified()
    {
        $Response = new Response();
        $time = time();

        $res = $Response->lastModified($time);
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
        //断言值
        $this->assertEquals($res->getHeader()['Last-Modified'], $time);
    }

    //检查Expires
    public function testExpires()
    {
        $Response = new Response();

        $time = time();

        $res = $Response->expires($time);
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
        //断言值
        $this->assertEquals($res->getHeader()['Expires'], $time);
    }

    //检查ETag
    public function testETag()
    {
        $Response = new Response();

        $eTag = 'W/"964-5a1a530259b40"';

        $res = $Response->eTag($eTag);
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
        //断言值
        $this->assertEquals($res->getHeader()['ETag'], $eTag);
    }

    //检查页面输出类型
    public function testContentType()
    {
        $Response = new Response();

        $string = 'text/html';
        //断言为字符串
        $this->assertIsString($string);

        $res = $Response->contentType($string);

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
        //断言值
        $this->assertEquals($res->getHeader()['Content-Type'], 'text/html; charset=utf-8');
    }

    //检查获取头部信息
    public function testGetHeader()
    {
        $Response = new Response();

        $res = $Response->header('modified', 'Wed, 25 Mar 2020 03:08:53 GMT');

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
        //断言值
        $this->assertEquals($res->getHeader()['modified'], 'Wed, 25 Mar 2020 03:08:53 GMT');
    }

    //检查获取原始数据
    public function testGetData()
    {
        $data['id'] = 1;
        $data['name'] = '呵呵哒';
        $Response = new Response($data);

        $res = $Response->getData();
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为数组
        $this->assertIsArray($res);
        //断言id值
        $this->assertEquals($res['id'], 1);
        //断言name值
        $this->assertEquals($res['name'], '呵呵哒');
    }

    //检查获取输出数据
    public function testGetContent()
    {
        $Response = new Response();
        $res = $Response->content('检查设置页面输出内容');
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);

        $content = $Response->getContent();
        //断言不为空
        $this->assertNotEmpty($content);
        //断言为字符串
        $this->assertIsString($content);
        //断言值
        $this->assertEquals($content, '检查设置页面输出内容');
    }

    //检查获取状态码
    public function testGetCode()
    {
        $Response = new Response();

        $res = $Response->getCode();
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsInt($res);
        //断言值
        $this->assertEquals($res, 200);
    }
}