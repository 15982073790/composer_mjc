<?php

namespace Mrstock\Mjc\Log;

/**
 * debug 日志级别 只在开启APP_DEBUG时记录
 * *
 */
class LogDebugLevel
{

    /**
     *调试日志
     * @var string
     */
    const DEBUG = 'DEBUG';

    /**
     * 数据操作类专门记录sql数据使用
     *
     * @var string
     */
    const SQLRECORD = 'SQLRECORD';


    /**
     * REDIS 查询日志
     * @var string
     */
    const REDISRECORD = 'REDISRECORD';

    /**
     * 文件访问日志
     * @var string
     */
    const FILERECORD = 'FILERECORD';

    /**
     * rpc 调式
     * @var string
     */
    const RPCDEBUG = 'RPCDEBUG';

    /**
     * cli echo
     * @var string
     */
    const CLIECHO = 'CLIECHO';


}