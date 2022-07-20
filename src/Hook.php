<?php

namespace Mrstock\Mjc;

use Mrstock\Helper\Loader;
use Mrstock\Helper\Config;

/**
 *
 * 在某一动作开始或者结束的时候会触发的方法
 * 比如支付成功会给你发一个支付成功的提醒,注册、登录、签到等操作完成后加股豆
 * 钩子则是对用户动作的处理（很典型的就是点击事件）
 * 钩子相当于事件 去掉后对流程没有影响
 * @author luar
 *
 */
class Hook
{
    /**
     * 钩子行为定义
     * @var array
     */
    private $tags = [];

    /**
     * 绑定行为列表
     * @var array
     */
    protected $bind = [];

    /**
     * 入口方法名称
     * @var string
     */
    private static $portal = 'run';

    /**
     * 应用对象
     * @var App
     */
    protected $app;

    public function __construct(App $app)
    {
        $tags = [];

        if (is_array($tags)) {
            $this->import($tags);
        }
        $this->app = $app;
    }

    /**
     * 指定入口方法名称
     * @access public
     * @param string $name 方法名
     * @return $this
     */
    public function portal($name)
    {
        self::$portal = $name;
        return $this;
    }

    /**
     * 指定行为标识 便于调用
     * @access public
     * @param string|array $name 行为标识
     * @param mixed $behavior 行为
     * @return $this
     */
    public function alias($name, $behavior = null)
    {
        if (is_array($name)) {
            $this->bind = array_merge($this->bind, $name);
        } else {
            $this->bind[$name] = $behavior;
        }

        return $this;
    }

    /**
     * 动态添加行为扩展到某个标签
     * @access public
     * @param string $tag 标签名称
     * @param mixed $behavior 行为名称
     * @param bool $first 是否放到开头执行
     * @return void
     */
    public function add($tag, $behavior, $first = false)
    {
        isset($this->tags[$tag]) || $this->tags[$tag] = [];

        if (is_array($behavior) && !is_callable($behavior)) {
            if (!array_key_exists('_overlay', $behavior)) {
                $this->tags[$tag] = array_merge($this->tags[$tag], $behavior);
            } else {
                unset($behavior['_overlay']);
                $this->tags[$tag] = $behavior;
            }
        } elseif ($first) {
            array_unshift($this->tags[$tag], $behavior);
        } else {
            $this->tags[$tag][] = $behavior;
        }
    }

    /**
     * 批量导入插件
     * @access public
     * @param array $tags 插件信息
     * @param bool $recursive 是否递归合并
     * @return void
     */
    public function import(array $tags, $recursive = true)
    {
        if ($recursive) {
            foreach ($tags as $tag => $behavior) {
                $this->add($tag, $behavior);
            }
        } else {
            $this->tags = $tags + $this->tags;
        }
    }

    /**
     * 获取插件信息
     * @access public
     * @param string $tag 插件位置 留空获取全部
     * @return array
     */
    public function get($tag = '')
    {
        if (empty($tag)) {
            //获取全部的插件信息
            return $this->tags;
        }

        return array_key_exists($tag, $this->tags) ? $this->tags[$tag] : [];
    }

    /**
     * 监听标签的行为
     * @access public
     * @param string $tag 标签名称
     * @param mixed $params 传入参数
     * @param bool $once 只获取一个有效返回值
     * @param bool $resetParams 是否用上一个返回值重置参数
     * @return mixed
     */
    public function listen($tag, $params = null, $once = false, $resetParams = false)
    {

        $results = [];
        $tags = $this->get($tag);

        $preResult = null;
        foreach ($tags as $key => $name) {
            $args = $params;
            if ($resetParams && $preResult) {
                $args = $preResult;
            }
            $preResult = $this->execTag($name, $tag, $args);
            $results[$key] = $preResult;

            /*if (false === $results[$key] || (!is_null($results[$key]) && $once)) {
                break;
            }*/
        }

        return $once ? end($results) : $results;
    }

    /**
     * 执行行为
     * @access public
     * @param mixed $class 行为
     * @param mixed $params 参数
     * @return mixed
     */
    public function exec($class, $params = null)
    {
        if ($class instanceof \Closure || is_array($class)) {
            $method = $class;
        } else {
            if (isset($this->bind[$class])) {
                $class = $this->bind[$class];
            }
            $method = [$class, self::$portal];
        }

        return $this->app->invoke($method, [$params]);
    }

    /**
     * 执行某个标签的行为
     * @access protected
     * @param mixed $class 要执行的行为
     * @param string $tag 方法名（标签名）
     * @param mixed $params 参数
     * @return mixed
     */
    protected function execTag($class, $tag = '', $params = null)
    {
        $method = Loader::parseName($tag, 1, false);

        if ($class instanceof \Closure) {
            $call = $class;
            $class = 'Closure';
        } elseif (strpos($class, '::')) {
            $call = $class;
        } else {

            $obj = Container::get($class);

            if (!is_callable([$obj, $method])) {
                $method = self::$portal;
            }

            $call = [$class, $method];
            $class = $class . '->' . $method;
        }

        $result = $this->app->invoke($call, [$params]);

        return $result;
    }

    public function __debugInfo()
    {
        $data = get_object_vars($this);
        unset($data['app']);

        return $data;
    }
}
