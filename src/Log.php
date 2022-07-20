<?php

namespace Mrstock\Mjc;

use Mrstock\Helper\Tool;
use Mrstock\Helper\Config;
use Mrstock\Mjc\App;
use Mrstock\Mjc\Log\LogLevel;
use Mrstock\Mjc\Log\LogDebugLevel;

/**
 * 日志记录器
 * *
 */
class Log
{

    // 应用对象
    protected $app;

    // 日志目录
    protected static $logDir;

    // 日志类型
    protected static $logLevel = [];

    // 调式日志类型
    protected static $logDebugLevel = [];

    // 需要记录 trace 的类型
    protected static $recordTrace = array(
        "DBERR",
        "REDISERR",
        "DBROLLBACK"
    );

    // 需要记录 运行时间 的类型
    protected static $recordRunTime = array(
        "ROUTERECORD",
        "ACCESS"
    );

    // 不需要记录字段
    protected static $filterFields = [];

    // 索引字段
    protected static $indxFields = [];

    // 日志配置文件
    protected static $config = [];

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function record($message, $level = LogLevel::DEBUG)
    {
        $this->write($message, $level);

        return true;
    }

    public function write($message, $level = LogLevel::DEBUG)
    {
        self::config();

        self::setLogLevel();

        $level = strtoupper($level);

        if (!in_array($level, self::$logLevel)) {
            return;
        }

        //调试类型 在 非调试模式下 不输出
        if (!(defined('APP_DEBUG') && APP_DEBUG) && in_array($level, self::$logDebugLevel)) {
            return;
        }

        $appPath = APP_PATH;
        $logFile = self::getFileName($level, $appPath);

        $content = $this->logJsonContent($message, $level, $appPath);

        if ($content === false) {
            return;
        }

        $res = file_put_contents($logFile, $content, FILE_APPEND);

        return $res;
    }

    //记录日志，便于调试 wangsongqing
    function writeLog($content, $file = 'log.log')
    {

        if (preg_match('/php/i', $file) || is_array($content)) return;
        $logDir = '/data/logs/';
        if (!file_exists($logDir)) {
            @mkdir($logDir);
            @chmod($logDir, 0777);
        }

        $content = "【" . date("Y-m-d H:i:s", time()) . "】\t\t" . $content . "\r\n";
        file_put_contents($logDir . '/' . $file, $content, FILE_APPEND);
        return true;
    }

    /**
     * 获取 日志模块的配置项
     */
    protected static function config()
    {
        if (empty(self::$config)) {
            self::$config = Config::get("log");
        }

        if (isset(self::$config['dir']) && empty(self::$logDir)) {
            self::$logDir = self::$config['dir'];
        }

        // 需要单独加索引的字段
        if (isset(self::$config['indxfields']) && empty(self::$indxFields)) {
            self::$indxFields = self::$config['indxfields'];
        }

        // 需要过滤的字段
        if (isset(self::$config['filterfields']) && empty(self::$filterFields)) {
            self::$filterFields = self::$config['filterfields'];
        }
    }

    protected static function getFileName($level, $appPath)
    {
        $logApp = str_replace('//', '_', $appPath);
        $logApp = str_replace('/', '_', $logApp);
        $logApp = str_replace('\\', '_', $logApp);
        $logApp = str_replace(':', '_', $logApp);

        $dir = self::$logDir;

        if (in_array($level, self::$logDebugLevel)) {
            $dir = $dir . "/debug";
        }

        Tool::mkDir($dir);

        $logFile = $dir . '/' . $logApp . '_' . $level . "_" . @date('Ymd', time()) . ".log";
        return $logFile;
    }

    protected static function setLogLevel()
    {
        if (self::$logLevel != null && is_array(self::$logLevel) && count(self::$logLevel) > 0)
            return;

        $consts = @new \ReflectionClass(get_class(new Log\LogLevel()));
        $levels = $consts->getConstants();
        self::$logLevel = array_keys($levels);

        if (self::$logDebugLevel != null && is_array(self::$logDebugLevel) && count(self::$logDebugLevel) > 0)
            return;
        $consts = @new \ReflectionClass(get_class(new Log\LogDebugLevel()));
        $levels = $consts->getConstants();
        self::$logDebugLevel = array_keys($levels);
    }

    protected function logJsonContent($message, $level, $appPath)
    {
        $now = time();


        $nowTime = Tool::get_microtime_format(Tool::msectime() * 0.001);
        $argv = json_encode($this->app->request->server['argv'], JSON_UNESCAPED_UNICODE);
        $tmp = $this->app->request->param;

        $this->setFilterField($tmp);

        $param = json_encode($tmp, JSON_UNESCAPED_UNICODE);

        if (in_array($level, self::$recordTrace)) {
            $trace = debug_backtrace();
            $message .= PHP_EOL . $this->logTrace();
        }

        $runTime = 0;
        if (in_array($level, self::$recordRunTime)) {

            $runTime = round(microtime(true) - $this->app->request->server['RUN_TIME_FLOAT'], 6) * 1000;
        }

        $data = array();
        $data['apppath'] = $appPath;
        $data['time'] = $nowTime;
        $data['level'] = $level;
        $data['argv'] = $argv;
        $data['request'] = $param;
        $data['rpc_msg_id'] = $tmp['rpc_msg_id'];
        $data['rpc_msg_time'] = $tmp['rpc_msg_time'];
        $data['apicode'] = md5(strtolower($tmp['site'] . $tmp['v'] . $tmp['c'] . $tmp['a']));
        $data['message'] = strval($message);

        $data['ip'] = Tool::getIp();

        $data['runtime'] = $runTime;
        $data['requesturi'] = isset($this->app->request->server['REQUEST_URI']) ? $this->app->request->server['REQUEST_URI'] : '';

        $this->setIndxField($data);

        $content = json_encode($data) . PHP_EOL;

        return $content;
    }

    protected function logTrace()
    {
        $trace = debug_backtrace();

        $caller = array_shift($trace);
        $functionName = $caller['function'];
        $result = sprintf('%s: Called from %s:%s', $functionName, $caller['file'], $caller['line']) . "\n";
        foreach ($trace as $entry_id => $entry) {
            $entry['file'] = $entry['file'] ?: '-';
            $entry['line'] = $entry['line'] ?: '-';
            if (empty($entry['class'])) {
                $result .= sprintf('%s %3s. %s() %s:%s', $functionName, $entry_id + 1, $entry['function'], $entry['file'], $entry['line']) . "\n";
            } else {
                $result .= sprintf('%s %3s. %s->%s() %s:%s', $functionName, $entry_id + 1, $entry['class'], $entry['function'], $entry['file'], $entry['line']) . "\n";
            }
        }
        return $result;
    }

    /**
     * 处理需要过滤的字段
     *
     * @param unknown $tmp
     */
    protected function setFilterField(&$tmp)
    {
        if (is_array($tmp)) {
            foreach ($tmp as $k => $v) {
                if (isset(self::$filterFields[$k])) {
                    unset($tmp[$k]);
                }
            }
        }
    }

    /**
     * 处理单独索引字段
     *
     * @param unknown $data
     */
    protected function setIndxField(&$data)
    {
        if (is_array(self::$indxFields) && count(self::$indxFields) > 0) {
            foreach (self::$indxFields as $field) {
                $fieldValue = '';
                if (isset($this->app->request->param[$field])) {
                    $fieldValue = $this->app->request->param[$field];
                }
                $data[$field] = $fieldValue;
            }
        }
    }
}