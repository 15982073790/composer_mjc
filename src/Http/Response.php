<?php

namespace Mrstock\Mjc\Http;

use Mrstock\Mjc\Container;

class Response
{
    /**
     * 原始数据
     * @var mixed
     */
    protected $data;

    /**
     * 应用对象实例
     * @var App
     */
    protected $app;

    /**
     * 当前contentType
     * @var string
     */
    protected $contentType = 'text/html';

    /**
     * 字符集
     * @var string
     */
    protected $charset = 'utf-8';

    /**
     * 状态码
     * @var integer
     */
    protected $code = 200;

    /**
     * 是否允许请求缓存
     * @var bool
     */
    protected $allowCache = true;

    /**
     * 输出参数
     * @var array
     */
    protected $options = [];

    /**
     * header参数
     * @var array
     */
    protected $header = [];

    /**
     * 输出内容
     * @var string
     */
    protected $content = null;

    /**
     * 架构函数
     * @access public
     * @param mixed $data 输出数据
     * @param int $code
     * @param array $header
     * @param array $options 输出参数
     */
    public function __construct($data = '', $code = 200, array $header = [], $options = [])
    {
        $this->data($data);

        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }

        $this->contentType($this->contentType, $this->charset);

        $this->code = $code;
        $this->header = array_merge($this->header, $header);
        $this->app = Container::get("app");
    }

    /**
     * 创建Response对象
     * @access public
     * @param mixed $data 输出数据
     * @param string $type 输出类型
     * @param int $code
     * @param array $header
     * @param array $options 输出参数
     * @return Response
     */
    public static function create($data = '', $type = '', $code = 200, array $header = [], $options = [])
    {
        $type = self::parseType($type);
        $class = false !== strpos($type, '\\') ? $type : '\\Mrstock\Mjc\Http\\' . ucfirst(strtolower($type));

        if (class_exists($class)) {
            return new $class($data, $code, $header, $options);
        }

        return new static($data, $code, $header, $options);
    }

    /**
     * 发送数据到客户端
     * @access public
     * @return void
     * @throws \InvalidArgumentException
     */
    public function send()
    {
        if (PHP_SAPI == 'cli') {
            $this->cliSendCodeAndHeader();
        } else {
            $this->sendCodeAndHeader();
        }

        // 处理输出数据
        $data = $this->getContent();
        $this->sendData($data);
    }

    protected function sendCodeAndHeader()
    {
        // 发送状态码
        http_response_code($this->code);

        // 发送头部信息
        foreach ($this->header as $name => $val) {
            header($name . (!is_null($val) ? ':' . $val : ''));
        }
    }

    /**
     * cli 输出 code 和 header
     */
    protected function cliSendCodeAndHeader()
    {
        \Workerman\Protocols\Http::header('HTTP/1.1 ' . $this->code);

        // 发送头部信息
        foreach ($this->header as $name => $val) {
            $header = $name . (!is_null($val) ? ':' . $val : '');
            \Workerman\Protocols\Http::header($header);
        }

    }

    /**
     * 适配jsonp
     * @param unknown $type
     * @return string
     */
    protected static function parseType($type)
    {
        if ($type == "json" && self::isJsonp()) {
            $type = "jsonp";
        }
        return $type;
    }

    /**
     * 判断是否jsonp
     * @return boolean
     */
    protected static function isJsonp()
    {
        $app = Container::get("app");
        if (isset($app->request->param['callback']) && $app->request->param['callback']) {
            return true;
        }
        return false;
    }

    /**
     * 处理数据
     * @access protected
     * @param mixed $data 要处理的数据
     * @return mixed
     */
    protected function output($data)
    {
        return $data;
    }

    /**
     * 输出数据
     * @access protected
     * @param string $data 要处理的数据
     * @return void
     */
    protected function sendData($data)
    {

        echo $data;
    }

    /**
     * 输出的参数
     * @access public
     * @param mixed $options 输出参数
     * @return $this
     */
    public function options($options = [])
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * 输出数据设置
     * @access public
     * @param mixed $data 输出数据
     * @return $this
     */
    public function data($data)
    {
        $this->data = $data;

        return $this;
    }


    /**
     * 设置响应头
     * @access public
     * @param string|array $name 参数名
     * @param string $value 参数值
     * @return $this
     */
    public function header($name, $value = null)
    {
        if (is_array($name)) {
            $this->header = array_merge($this->header, $name);
        } else {
            $this->header[$name] = $value;
        }

        return $this;
    }

    /**
     * 设置页面输出内容
     * @access public
     * @param mixed $content
     * @return $this
     */
    public function content($content)
    {
        if (null !== $content && !is_string($content) && !is_numeric($content) && !is_callable([$content, '__toString',])) {

            throw new \InvalidArgumentException(sprintf('variable type error： %s', gettype($content)));
        }

        $this->content = (string)$content;

        return $this;
    }

    /**
     * 发送HTTP状态
     * @access public
     * @param integer $code 状态码
     * @return $this
     */
    public function code($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * LastModified
     * @access public
     * @param string $time
     * @return $this
     */
    public function lastModified($time)
    {
        $this->header['Last-Modified'] = $time;

        return $this;
    }

    /**
     * Expires
     * @access public
     * @param string $time
     * @return $this
     */
    public function expires($time)
    {
        $this->header['Expires'] = $time;

        return $this;
    }

    /**
     * ETag
     * @access public
     * @param string $eTag
     * @return $this
     */
    public function eTag($eTag)
    {
        $this->header['ETag'] = $eTag;

        return $this;
    }


    /**
     * 页面输出类型
     * @access public
     * @param string $contentType 输出类型
     * @param string $charset 输出编码
     * @return $this
     */
    public function contentType($contentType, $charset = 'utf-8')
    {
        $this->header['Content-Type'] = $contentType . '; charset=' . $charset;

        return $this;
    }

    /**
     * 获取头部信息
     * @access public
     * @param string $name 头部名称
     * @return mixed
     */
    public function getHeader($name = '')
    {
        if (!empty($name)) {
            return isset($this->header[$name]) ? $this->header[$name] : null;
        }

        return $this->header;
    }

    /**
     * 获取原始数据
     * @access public
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 获取输出数据
     * @access public
     * @return mixed
     */
    public function getContent()
    {
        if (null == $this->content) {
            $content = $this->output($this->data);

            if (null !== $content && !is_string($content) && !is_numeric($content) && !is_callable([$content, '__toString',])) {
                //var_dump($content);exit;
                if (is_array($content) && $content["message"]) {
                    throw  new \Exception($content["message"]);
                }
                throw new \InvalidArgumentException(sprintf('variable type error： %s', gettype($content)));
            }

            $this->content = (string)$content;
        }

        return $this->content;
    }

    /**
     * 获取状态码
     * @access public
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

}