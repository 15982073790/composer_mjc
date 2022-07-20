<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/15
 * Time: 18:01
 */

namespace Mrstock\Mjc\Test;


use Mrstock\Helper\Config;
use Mrstock\Mjc\Control;
use Mrstock\Mjc\Http\Response;
use PHPUnit\Framework\TestCase;

class ControlTest extends TestCase
{
    //检查json
    public function testJson()
    {
        if (!defined('VENDOR_DIR')) {
            define('VENDOR_DIR', __DIR__ . '/../vendor');
        }
        $control = new Control();
        $Response = new Response();
        $config['rpcerrorcode']['base'] = 10001;
        Config::set($config);

        $res = $control->json('212211221', -1);

        //断言不为空
        $this->assertNotEmpty($res);
        //断言为对象
        $this->assertIsObject($res);
        //断言不为空
        $this->assertNotEmpty($res->getData());
        //断言为对象
        $this->assertIsArray($res->getData());
        //断言message值
        $this->assertEquals($res->getData()['message'], '212211221');
        //断言code值
        $this->assertEquals($res->getData()['code'], -1);
    }
}