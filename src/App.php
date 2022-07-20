<?php

namespace Mrstock\Mjc;

use Mrstock\Helper\Config;
use Mrstock\Mjc\Http\Response;


class App extends Container
{

    public function __construct()
    {
        if (function_exists('date_default_timezone_set')) {
            @date_default_timezone_set('Asia/Shanghai');
        }
        //获取配置文件
        Config::register();

        if (defined('IS_CLI') && IS_CLI) {
            $this->instance('error', new Errorcli());

        }

        //注册错误托管函数
        $this->error->register();
        //注册应用autoload函数

        $this->router->register();
    }

    public function run()
    {
        Container::clear();
        //App对象 赋值 Container
        static::setInstance($this);
        //绑定app
        $this->instance('app', $this);
        //重置Request对象
        $this->request = Container::get("request", [], true);
        //重置Debug
        $this->debug = Container::get("debug", [], true);
        //重置response对象
        $this->response = Response::create('', 'json');
        //重置中间件
        $this->middleware = Container::get("middleware", [], true);

        //重置行为
        $this->hook = Container::get("hook", [], true);

        //Facade\Hook::listen("app_begin");

        \Mrstock\Mjc\Facade\Middleware::add('router');
        $response = \Mrstock\Mjc\Facade\Middleware::dispatch($this->request);

        //Facade\Hook::listen("app_end");

        return $response;
    }
}