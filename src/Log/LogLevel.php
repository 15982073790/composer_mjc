<?php

namespace Mrstock\Mjc\Log;

/**
 * 日志级别
 * *
 */
class LogLevel extends LogDebugLevel
{
    /**
     * 系统错误
     * @var string
     */
    const ERR = 'ERR';
    /**
     * 系统异常
     * @var string
     */
    const EXP = 'EXP';
    /**
     * 系统提示或者警告日志
     * @var string
     */
    const NOTICE = 'NOTICE';
    /**
     * 数据库操作错误
     *
     * @var string
     */
    const DBERR = 'DBERR';

    /**
     * 数据库慢查询日志
     * @var string
     */
    const DBSLOW = 'DBSLOW';

    /**
     * 数据库事务回滚日志
     * @var string
     */
    const DBROLLBACK = 'DBROLLBACK';


    /**
     * redis 错误
     *
     * @var string
     */
    const REDISERR = 'REDISERR';


    /**
     * 访问日志不带结果
     *
     * @var string
     */
    const ACCESS = 'ACCESS';

    /**
     * 业务层异常
     *
     * @var string
     */
    const ROUTEERR = 'ROUTEERR';

    /**
     * 路由错误 找不到控制器
     *
     * @var string
     */
    const ROUTENONE = 'ROUTENONE';

    /**
     * curl 错误日志
     */
    const CURLERR = "CURLERR";
    /**
     * mq 错误日志
     */
    const MQERR = "MQERR";
    /**
     * rpc 错误日志
     */
    const RPCERR = "RPCERR";
    /**
     * 访问记录 记录返回结果
     *
     * @var string
     */
    const ROUTERECORD = 'ROUTERECORD';
}