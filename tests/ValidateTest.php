<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/16
 * Time: 15:08
 */

namespace Mrstock\Mjc\Test;


use Mrstock\Mjc\Http\Request;
use Mrstock\Mjc\Validate;
use PHPUnit\Framework\TestCase;

class ValidateTest extends TestCase
{
    //检查验证数组中的值
    public function testValidate()
    {
        $objValidate = new Validate();

        $request = new Request();
        $request->param['id'] = 1;
        $request->param['name'] = '呵呵哒';

        $objValidate->validateparam = [
            ["input" => $request->param['id'], "require" => "true", "message" => 'id 不能为空'],
            ["input" => $request->param['name'], "require" => "true", "message" => 'name 不能为空'],
            ["input" => $request->param['mobile'], "require" => "true", "message" => 'mobile 不能为空'],
        ];
        $error = $objValidate->validate();

        //断言不为空
        $this->assertNotEmpty($error);
        //断言为字符串
        $this->assertIsString($error);
        //断言值
        $this->assertEquals($error, 'mobile 不能为空');
    }

    //检查正则表达式运算
    public function testCheck()
    {
        $objValidate = new Validate();

        $res = $objValidate->check(13548316475, 'mobile');
        //断言不为空
        $this->assertNotEmpty($res);
        //断言为字符串
        $this->assertIsBool($res);
        //断言值
        $this->assertEquals($res, 1);
    }

    //检查需要验证的内容
    public function testSetValidate()
    {
        $objValidate = new Validate();

        $request = new Request();
        $request->param['id'] = 1;
        $request->param['name'] = '呵呵哒';

        $validateparam = [
            ["input" => $request->param['id'], "require" => "true", "message" => 'id 不能为空'],
            ["input" => $request->param['name'], "require" => "true", "message" => 'name 不能为空'],
            ["input" => $request->param['mobile'], "require" => "true", "message" => 'mobile 不能为空'],
        ];

        $objValidate->setValidate($validateparam);

        //断言不为空
        $this->assertNotEmpty($objValidate->validateparam);
        //断言为字符串
        $this->assertIsArray($objValidate->validateparam);
        //断言值
        $this->assertEquals($objValidate->validateparam[0]['result'], 1);
    }
}