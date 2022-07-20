<?php

namespace Mrstock\Mjc\Exception;

class ControlException extends \Exception
{
    /**
     * 用于保存错误代码
     * @var integer
     */
    protected $severity;

    /**
     * 错误异常构造函数
     * @access public
     * @param string $message 错误详细信息
     * @param integer $code 错误代码
     * @param integer $status http 状态嘛
     * @param string $file 出错文件路径
     * @param integer $line 出错行号
     */
    public function __construct($message = null, $code = null, $status = 200, $file = '', $line = '')
    {
        $this->message = $message;
        $this->file = $file;
        $this->line = $line;
        $this->code = $code;
        $this->status = $status;
    }

    /**
     * 获取错误级别
     * @access public
     * @return integer 错误级别
     */
    final public function getSeverity()
    {
        return $this->severity;
    }

    final public function getStatus()
    {
        return $this->status;
    }
}
