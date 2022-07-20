<?php

/*cli模式我们要保证的是php不退出，所以和http的异常处理有所不同*/

namespace Mrstock\Mjc;

use Mrstock\Helper\Output;
use Mrstock\Mjc\Facade\Log;
use Mrstock\Mjc\Log\LogLevel;

class Errorcli
{

    /**
     * 注册异常处理 + 该托管任务会截断流程
     *
     * @access public
     * @return void
     */
    public function register()
    {
//        set_error_handler([__CLASS__, 'cliError']);
        set_exception_handler([__CLASS__, 'cliException']);
        register_shutdown_function([__CLASS__, 'cliShutdown']);
    }

    static public function cliError($errno, $errstr, $errfile, $errline)
    {
        $error = array();
        $error['errno'] = $errno;
        $error['message'] = $errstr;
        $error['file'] = $errfile;
        $error['line'] = $errline;
        Log::write(print_r($error, true), LogLevel::NOTICE);
        throw new \Exception($errstr);

    }

    /**
     * 自定义异常处理(中断流程)
     *
     * @access public
     * @param mixed $e
     *            异常对象
     */
    static function cliException($e)
    {
        $error = array();
        $error['message'] = $e->getMessage();
        $status = 200;
        $code = $e->getcode();
        // 自定义ControlExceptio/ErrorException类处理
        if (method_exists($e, "getStatus")) {
            $status = $e->getStatus();
        }

        Log::write(print_r($error, true), LogLevel::EXP);

        $resp = Output::response($error['message'], $code, $status);
        $resp->send();

    }

    // 致命错误捕获(中断流程)
    static function cliShutdown()
    {

        $lasterror = error_get_last();
        if ($lasterror) {
            $error = array();
            $error['message'] = $lasterror['message'];
            $error['file'] = $lasterror['file'];
            $error['line'] = $lasterror['line'];
            Log::write(print_r($error, true), LogLevel::ERR);
        } else {
            Log::write("clishutdown", LogLevel::ERR);
        }
    }


}
