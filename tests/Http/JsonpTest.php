<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/14
 * Time: 9:43
 */

namespace Mrstock\Mjc\Test\Http;


use PHPUnit\Framework\TestCase;

class JsonpTest extends TestCase
{
    //检查Options输出参数
    public function testOptions()
    {
        $options = [
            'var_jsonp_handler' => 'callback',
            'default_jsonp_handler' => 'jsonpReturn',
            'json_encode_param' => JSON_UNESCAPED_UNICODE,
        ];

        //断言不为空
        $this->assertNotEmpty($options);
        //断言为数组
        $this->assertIsArray($options);
        //断言var_jsonp_handler值
        $this->assertEquals($options['var_jsonp_handler'], 'callback');
        //断言default_jsonp_handler值
        $this->assertEquals($options['default_jsonp_handler'], 'jsonpReturn');
        //断言json_encode_param值
        $this->assertEquals($options['json_encode_param'], JSON_UNESCAPED_UNICODE);
    }

    //断言ContentType值
    public function testContentType()
    {
        $content_type = 'text/plain';
        //断言不为空
        $this->assertNotEmpty($content_type);
        //断言值
        $this->assertEquals($content_type, 'text/plain');
    }

    //断言OutPutData入参
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