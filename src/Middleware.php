<?php

namespace Mrstock\Mjc;

use InvalidArgumentException;
use LogicException;
use Mrstock\Helper\Config;
use Mrstock\Mjc\Http\Request;
use Mrstock\Mjc\Http\Response;

/**
 *
 * 对项目请求做处理
 * 中间件可以对参数进行参数过滤
 * 中间件可以对于这个请求来判断用户是否有权限，或者判断用户是够请求更多
 * 中间件相当于过滤器 去掉后对流程有影响
 * @author luar
 *
 */
class Middleware
{
    protected $queue = [];
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        //获取全局中间件
        $middlewares = [];

        if (is_array($middlewares)) {
            $this->import($middlewares);
        }
    }

    /**
     * 导入中间件
     * @access public
     * @param array $middlewares
     * @param string $type 中间件类型
     */
    public function import(array $middlewares = [], $type = 'route')
    {
        foreach ($middlewares as $middleware) {
            $this->add($middleware, $type);
        }
    }

    /**
     * 注册中间件
     * @access public
     * @param mixed $middleware
     * @param string $type 中间件类型
     */
    public function add($middleware, $type = 'route')
    {
        if (is_null($middleware)) {
            return;
        }

        $middleware = $this->buildMiddleware($middleware, $type);

        if ($middleware) {
            $this->queue[$type][] = $middleware;
        }
    }

    /**
     * 注册控制器中间件
     * @access public
     * @param mixed $middleware
     */
    public function controller($middleware)
    {
        return $this->add($middleware, 'controller');
    }

    /**
     * 移除中间件
     * @access public
     * @param mixed $middleware
     * @param string $type 中间件类型
     */
    public function unshift($middleware, $type = 'route')
    {
        if (is_null($middleware)) {
            return;
        }

        $middleware = $this->buildMiddleware($middleware, $type);

        if ($middleware) {
            array_unshift($this->queue[$type], $middleware);
        }
    }

    /**
     * 获取注册的中间件
     * @access public
     * @param string $type 中间件类型
     */
    public function all($type = 'route')
    {
        return $this->queue[$type] ?: [];
    }

    /**
     * 清除中间件
     * @access public
     */
    public function clear()
    {
        $this->queue = [];

        return true;
    }

    /**
     * 中间件调度
     * @access public
     * @param Request $request
     * @param string $type 中间件类型
     */
    public function dispatch(Request $request, $type = 'route')
    {
        return call_user_func($this->resolve($type), $request);
    }

    /**
     * 解析中间件
     * @access protected
     * @param mixed $middleware
     * @param string $type 中间件类型
     */
    protected function buildMiddleware($middleware, $type = 'route')
    {
        if (is_array($middleware)) {
            list($middleware, $param) = $middleware;
        }

        if ($middleware instanceof \Closure) {
            return [$middleware, isset($param) ? $param : null];
        }

        if (!is_string($middleware)) {
            throw new InvalidArgumentException('The middleware is invalid');
        }

        if (is_array($middleware)) {
            return $this->import($middleware, $type);
        }

        if (strpos($middleware, ':')) {
            list($middleware, $param) = explode(':', $middleware, 2);
        }

        return [[$this->app->make($middleware), 'handle'], isset($param) ? $param : null];
    }

    protected function resolve($type = 'route')
    {
        return function (Request $request) use ($type) {

            $middleware = array_shift($this->queue[$type]);

            if (null === $middleware) {
                throw new InvalidArgumentException('The queue was exhausted, with no response returned');
            }

            list($call, $param) = $middleware;

            $response = call_user_func_array($call, [$request, $this->resolve($type), $param]);

            if (!$response instanceof Response) {
                throw new LogicException('The middleware must return Response instance');
            }

            return $response;
        };
    }

    public function __debugInfo()
    {
        $data = get_object_vars($this);
        unset($data['app']);

        return $data;
    }
}
