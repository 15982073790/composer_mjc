<?php

namespace Mrstock\Mjc\Http;

class Request implements \ArrayAccess
{

    /**
     * 当前请求参数
     *
     * @var array
     */
    public $param = [];

    /**
     * 当前SERVER参数
     *
     * @var array
     */
    public $server = [];
    /**
     * 当前SERVER参数
     *
     * @var array
     */
    public $originparam = [];

    /**
     * /**
     * 架构函数
     *
     * @access public
     * @param array $options
     *            参数
     */
    public function __construct(array $options = [])
    {
        $this->init($options);
    }

    public function init(array $options = [])
    {
        $this->server = $_SERVER;
        //该区域为加代理服务器参数
//        if(empty($this->server['HTTP_USER_AGENT'])){
//            $this->server['HTTP_USER_AGENT'] = json_decode($_REQUEST['agentserver'],true)['HTTP_USER_AGENT'];
//        }
//        unset($_REQUEST['agentserver']); //将agentserver参数参数
        //结束
//		 $this->originparam=$_REQUEST;
        $_REQUEST["sysfiles"] = isset($_FILES) ? \Mrstock\Helper\Tool::arrToStr($_FILES) : "";
        $this->param = $_REQUEST;
        $this->server['RUN_TIME_FLOAT'] = microtime(true);

        /* unset($_GET);
         unset($_POST);
         unset($_REQUEST);*/
        unset($_FILES);
    }

    /**
     * 设置请求数据
     *
     * @access public
     * @param string $name
     *            参数名
     * @param mixed $value
     *            值
     */
    public function __set($name, $value)
    {
        return $this->param[$name] = $value;
    }

    /**
     * 获取请求数据的值
     *
     * @access public
     * @param string $name
     *            参数名
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->param[$name])) {
            return $this->param[$name];
        }
        return null;
    }

    /**
     * 判断属性是否空
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->param[$name]);
    }

    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return isset($this->param[$offset]);
    }

    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        return $this->__set($offset, $value);
    }

    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->param[$offset]);
    }
}
