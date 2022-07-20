<?php

namespace Mrstock\Mjc;

use Mrstock\Mjc\App;

class Debug
{

    /**
     * 区间时间信息
     *
     * @var array
     */
    protected $info = [];

    /**
     * 区间内存信息
     *
     * @var array
     */
    protected $mem = [];

    /**
     * 应用对象
     *
     * @var App
     */
    protected $app;

    /**
     * debug 信息
     * @var
     */
    protected $bodeData;

    /**
     * debug 序号
     * @var
     */
    protected $num;
    //test
    protected $scriptName = ['index.php', 'rpc.php', 'start.php', 'rpcstart.php', 'queue.php', 'inneruserpcstart.php'];

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function getStep()
    {
        if (!$this->isdeubg()) {
            return;
        }

        $this->num++;
        return 'Debug-Step' . $this->num;

    }

    /**
     * 记录时间（微秒）和内存使用情况
     *
     * @access public
     * @param string $name
     *            标记位置
     * @param mixed $value
     *            标记值 留空则取当前 time 表示仅记录时间 否则同时记录时间和内存
     * @return void
     */
    public function remark($name, $value = '')
    {
        if (!$this->isdeubg()) {
            return;
        }

        // 记录时间和内存使用
        $this->info[$name] = is_float($value) ? $value : microtime(true);

        if ('time' != $value) {
            $this->mem['mem'][$name] = is_float($value) ? $value : memory_get_usage();
            $this->mem['peak'][$name] = memory_get_peak_usage();
        }

    }

    /**
     * 统计某个区间的时间（微秒）使用情况
     *
     * @access public
     * @param string $start
     *            开始标签
     * @param string $end
     *            结束标签
     * @param integer|string $dec
     *            小数位
     * @return integer
     */
    public function getRangeTime($start, $end, $dec = 6)
    {
        if (!$this->isdeubg()) {
            return;
        }

        if (!isset($this->info[$end])) {
            $this->info[$end] = microtime(true);
        }

        return number_format(($this->info[$end] - $this->info[$start]), $dec);

    }

    /**
     * 统计从开始到统计时的时间（微秒）使用情况
     *
     * @access public
     * @param integer|string $dec
     *            小数位
     * @return integer
     */
    public function getUseTime($dec = 6)
    {
        return number_format((microtime(true) - $this->app->getBeginTime()), $dec);
    }

    /**
     * 获取当前访问的吞吐率情况
     *
     * @access public
     * @return string
     */
    public function getThroughputRate()
    {
        return number_format(1 / $this->getUseTime(), 2) . 'req/s';
    }

    /**
     * 记录区间的内存使用情况
     *
     * @access public
     * @param string $start
     *            开始标签
     * @param string $end
     *            结束标签
     * @param integer|string $dec
     *            小数位
     * @return string
     */
    public function getRangeMem($start, $end, $dec = 2)
    {
        if (!$this->isdeubg()) {
            return;
        }

        if (!isset($this->mem['mem'][$end])) {
            $this->mem['mem'][$end] = memory_get_usage();
        }

        $size = $this->mem['mem'][$end] - $this->mem['mem'][$start];
        $a = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pos = 0;

        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }

        return round($size, $dec) . " " . $a[$pos];
    }

    /**
     * 统计从开始到统计时的内存使用情况
     *
     * @access public
     * @param integer|string $dec
     *            小数位
     * @return string
     */
    public function getUseMem($dec = 2)
    {
        if (!$this->isdeubg()) {
            return;
        }

        $size = memory_get_usage() - $this->app->getBeginMem();
        $a = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pos = 0;

        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }

        return round($size, $dec) . " " . $a[$pos];
    }

    /**
     * 统计区间的内存峰值情况
     *
     * @access public
     * @param string $start
     *            开始标签
     * @param string $end
     *            结束标签
     * @param integer|string $dec
     *            小数位
     * @return string
     */
    public function getMemPeak($start, $end, $dec = 2)
    {
        if (!$this->isdeubg()) {
            return;
        }

        if (!$this->isdeubg()) {
            return;
        }

        if (!isset($this->mem['peak'][$end])) {
            $this->mem['peak'][$end] = memory_get_peak_usage();
        }

        $size = $this->mem['peak'][$end] - $this->mem['peak'][$start];
        $a = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pos = 0;

        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }

        return round($size, $dec) . " " . $a[$pos];
    }

    /**
     * 获取文件加载信息
     *
     * @access public
     * @param bool $detail
     *            是否显示详细
     * @return integer|array
     */
    public function getFile($detail = false)
    {
        if (!$this->isdeubg()) {
            return;
        }

        if ($detail) {
            $files = get_included_files();
            $info = [];

            foreach ($files as $key => $file) {
                $info[] = $file . ' ( ' . number_format(filesize($file) / 1024, 2) . ' KB )';
            }

            return $info;
        }

        return count(get_included_files());
    }

    public function traceHeader($header)
    {
        if (!$this->isdeubg()) {
            return;
        }

        $trace = debug_backtrace();

        $caller = array_shift($trace);
        $functionName = $caller['function'];
        $i = 100;
        foreach ($trace as $entry_id => $entry) {
            $entry['file'] = $entry['file'] ?: '-';
            $entry['line'] = $entry['line'] ?: '-';
            if (empty($entry['class'])) {
                $header['trace' . $i] = sprintf('%s %3s. %s() %s:%s', $functionName, $entry_id + 1, $entry['function'], $entry['file'], $entry['line']) . "\n";
            } else {
                $header['trace' . $i] = sprintf('%s %3s. %s->%s() %s:%s', $functionName, $entry_id + 1, $entry['class'], $entry['function'], $entry['file'], $entry['line']) . "\n";
            }
            $i++;
        }
        return $header;
    }

    public function data($name, $value = null)
    {
        if (!$this->isdeubg()) {
            return;
        }

        if (is_array($name)) {
            $this->bodyData = array_merge($this->bodyData, $name);
        } else {
            $this->bodyData[$name] = $value;
        }

        return $this;
    }

    public function getData($name = '')
    {
        if (!$this->isdeubg()) {
            return;
        }

        if (!empty($name)) {
            return isset($this->bodyData[$name]) ? $this->bodyData[$name] : null;
        }
        return $this->bodyData;

        return false;
    }

    public function isApi()
    {
        return in_array(basename($_SERVER['SCRIPT_NAME']), $this->scriptName);
    }

    protected function isdeubg()
    {
        return ($this->isApi() && $this->app->request->isdebug);
    }

    public function __debugInfo()
    {
        $data = get_object_vars($this);
        unset($data['app']);

        return $data;
    }
}
