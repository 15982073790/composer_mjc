<?php

namespace Mrstock\Mjc;

use Mrstock\Helper\Output;
use Mrstock\Mjc\Facade\Log;
use Mrstock\Mjc\Log\LogLevel;
use Mrstock\Orm\Model;

class Error
{

    /**
     * 注册异常处理 + 该托管任务会截断流程
     *
     * @access public
     * @return void
     */
    public function register()
    {
        set_error_handler([__CLASS__, 'appError']);
        set_exception_handler([__CLASS__, 'appException']);
        register_shutdown_function([__CLASS__, 'appShutdown']);
    }

    static public function appError($errno, $errstr, $errfile, $errline)
    {

        switch ($errno) {
            case E_NOTICE:
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                break;
            default:
                break;
        }

    }

    /**
     * 自定义异常处理
     *
     * @access public
     * @param mixed $e
     *            异常对象
     */
    static function appException($e)
    {
        $error = array();
        $error['message'] = $e->getMessage();
        $status = 200;
        $code = $e->getcode();
        // 自定义ControlExceptio/ErrorException类处理
        if (method_exists($e, "getStatus")) {
            $status = $e->getStatus();
        }
        if (Model::$open_begintransaction == true) {
            (new Model())->closeTransaction();
        }
        Log::write(print_r($error, true), LogLevel::ERR);
        $resp = Output::response($error['message'], $code, $status);
        $resp->send();

    }

    // 致命错误捕获
    static function appShutdown()
    {
        $error = error_get_last();
        if ($error) {
            switch ($error['type']) {
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR: // 256

                    $exception = new Exception\ErrorException($error['message'], -500, 500, $error['file'], $error['line']);
                    self::appException($exception);
                    break;
            }
        }
    }
}
