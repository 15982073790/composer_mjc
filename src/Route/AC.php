<?php

namespace Mrstock\Mjc\Route;

use Mrstock\Helper\Config;
use Mrstock\Helper\Loader;
use Mrstock\Mjc\Http\Request;
use Mrstock\Mjc\Http\Response;
use Mrstock\Mjc\Container;
use Mrstock\Mjc\Facade\Hook;
use Mrstock\Mjc\Middleware;

class AC
{

    //服务版本
    protected static $version_param;

    //服务版本默认值
    const VERSIOIN_DEFAULT = "application";

    // 模块参数
    protected static $module_param;

    // 默认模块
    const MODULE_DEFAULT = "App";

    // 控制器目录前缀
    const CONTROL_DIR = "Control";

    // 控制器参数
    protected static $control_param;

    // 控制器后缀
    const CONTROL_SUFFIX = "Control";

    // 操作参数
    protected static $action_param;

    // 操作后缀
    const ACTION_SUFFIX = "Op";

    // 应用对象
    protected $app;

    protected $currVersion;

    // 当前版本
    protected $currModule;

    // 当前控制器目录
    protected $currControlDir;

    // 当前命名空间
    protected $currNamespace;

    public function handle($request, \Closure $next)
    {
        $this->app = Container::get("app");
        self::$control_param = Config::get('ac.control');
        self::$action_param = Config::get('ac.action');
        self::$version_param = Config::get('ac.version');
        self::$module_param = Config::get('ac.module');


        $this->parseVersion();
        $this->parseModule();
        $this->parseControlDir();
        $this->parseNamespace();
        $this->importHook();
        $this->importMiddleware();

        //先执行全局中间件
        $obj = $this;
        $actionName = "befterDispatch";

        $this->app->middleware->add(function () use ($obj, $actionName) {
            return $this->app->invoke([$obj, $actionName
            ], []);
        });

        return $this->app->middleware->dispatch($this->app->request);
    }

    public function befterDispatch()
    {

        list ($className, $funName) = $this->parseControlAndAction();

        if (class_exists($className)) {

            $main = Container::get($className, [], true);
            $main->app = $this->app;

            if (method_exists($main, $funName)) {
                $response = $this->dispatch($main, $funName);
            } else {
                throw new \Exception($funName . "路由错误:" . __LINE__, -404);
            }

            return $response;
        } else {
            //如果有控制器中间件则执行
            $middlewares = $this->app->middleware->all("controller");
            if ($middlewares) {
                $response = $this->app->middleware->dispatch($this->app->request, "controller");
                return $response;
            }

        }

        throw new \Exception($className . "路由错误:" . __LINE__, -404);
    }

    /**
     * 控制器中间件调度
     *
     * @param unknown $obj
     */
    protected function dispatch($obj, $actionName)
    {
        if ($obj->middleware) {
            if (isset($obj->middleware['control']) && $obj->middleware['control']) {
                $this->app->middleware->import($obj->middleware['control'], 'controller');
            }
            if (isset($obj->middleware[$actionName]) && $obj->middleware[$actionName]) {
                $this->app->middleware->import($obj->middleware[$actionName], 'controller');
            }
        }

        $this->app->middleware->add(function () use ($obj, $actionName) {

            return $this->app->invoke([$obj, $actionName
            ], []);
        }, "controller");

        $response = $this->app->middleware->dispatch($this->app->request, 'controller');

        return $response;
    }

    /**
     * 导入作用于本版本的middleware
     */
    protected function importMiddleware()
    {
        //导入时会先判断类存不存在
        //版本全局中间件
        $configKey = "middlewares." . strtolower($this->currVersion) . "." . strtolower($this->currVersion);
        $middlewares = Config::get($configKey);

        if (is_array($middlewares)) {
            $this->app->middleware->import($middlewares);
        }

        if (strtolower($this->currVersion) == self::VERSIOIN_DEFAULT) {
            //兼容老全局配置文件
            $configKey = "middlewares." . self::VERSIOIN_DEFAULT;
            $middlewares = Config::get($configKey);

            if (is_array($middlewares)) {
                $this->app->middleware->import($middlewares);
            }
        }

        if (strtolower($this->currVersion) == self::VERSIOIN_DEFAULT) {
            //兼容老配置文件
            $configKey = "middlewares." . strtolower($this->currModule);
            $middlewares = Config::get($configKey);

            if (is_array($middlewares)) {
                $this->app->middleware->import($middlewares, 'controller');
            }
        }

        //版本相应模块中间件
        $configKey = "middlewares." . strtolower($this->currVersion) . "." . strtolower($this->currModule);
        $middlewares = Config::get($configKey);

        if (is_array($middlewares)) {
            $this->app->middleware->import($middlewares, 'controller');
        }

    }

    /**
     * 导入作用于本版本的hook
     */
    protected function importHook()
    {
        //版本全局hooks
        $configKey = "hooks." . strtolower($this->currVersion) . "." . strtolower($this->currVersion);
        $tags = Config::get($configKey);

        if (is_array($tags)) {
            Hook::import($tags);
        }

        if (strtolower($this->currVersion) == self::VERSIOIN_DEFAULT) {
            //兼容老配置文件
            $configKey = "hooks." . self::VERSIOIN_DEFAULT;
            $tags = Config::get($configKey);

            if (is_array($tags)) {
                Hook::import($tags);
            }
        }

        if (strtolower($this->currVersion) == self::VERSIOIN_DEFAULT) {
            //兼容老配置文件
            $configKey = "hooks." . strtolower($this->currModule);
            $tags = Config::get($configKey);

            if (is_array($tags)) {
                Hook::import($tags);
            }
        }

        //版本相应模块hooks
        $configKey = "hooks." . strtolower($this->currVersion) . "." . strtolower($this->currModule);
        $tags = Config::get($configKey);

        if (is_array($tags)) {
            Hook::import($tags);
        }
    }

    public function register() // 寻找项目内的类
    {
        $res = spl_autoload_register(array($this, 'autoload'
        ));

        return $res;
    }

    public function unregister() // 注销寻找项目内的类
    {
        $res = spl_autoload_unregister(array($this, 'autoload'
        ));
        return $res;
    }

    public function autoload($class)
    {
        $className = Loader::parseClass($class);
        $filePath = APP_PATH . '/' . $className . '.php';

        $this->parseVersion();
        $old = "";
        if (strtolower($this->currVersion) != self::VERSIOIN_DEFAULT) {
            $old = $filePath;
            $filePath = str_replace(self::VERSIOIN_DEFAULT . "/", "", $filePath);
            $filePath = str_replace("/" . $this->currVersion . "/", "/" . strtolower($this->currVersion) . "/", $filePath);
        }
        if (!is_file($filePath)) {
            $filePath = $old;
        }

        if (is_file($filePath)) {

            include_once($filePath);
        }

        return $filePath;
    }

    protected function parseVersion()
    {
        $this->currVersion = self::VERSIOIN_DEFAULT;
        if (isset($_REQUEST[self::$version_param]) && !empty($_REQUEST[self::$version_param])) {
            $this->currVersion = $_REQUEST[self::$version_param];
            $this->currVersion = ucfirst(strtolower($this->currVersion));
        }
        $version = config::get(self::$version_param);
        if (!empty($version) && isset($version[$this->currVersion]) && !empty($version[$this->currVersion])) {
            $this->currVersion = $version[$this->currVersion];

        }
        if (strtolower($this->currVersion) == "v1") {
            $this->currVersion = self::VERSIOIN_DEFAULT;
        }

    }

    protected function parseControlAndAction()
    {

        if (!isset($this->app->request->param[self::$control_param]) || empty($this->app->request->param[self::$control_param])) {
            throw new \Exception("路由错误:" . __LINE__, 404);
        }

        if (!isset($this->app->request->param[self::$action_param]) || empty($this->app->request->param[self::$action_param])) {
            throw new \Exception("路由错误:" . __LINE__, 404);
        }

        $controlName = ucfirst(strtolower($this->app->request->param[self::$control_param]));

        if (!preg_match('/^[A-Za-z](\w|\.)*$/', $controlName)) {
            throw new \Exception("control error:" . $controlName . ':' . __LINE__, 404);
        }

        $className = $this->currNamespace . $controlName . self::CONTROL_SUFFIX;

        // 兼容 c 参数值 驼峰
        if (!class_exists($className)) {
            $controlName = ucfirst($this->app->request->param[self::$control_param]);

            $className = $this->currNamespace . $controlName . self::CONTROL_SUFFIX;
        }

        $actionName = lcfirst($this->app->request->param[self::$action_param]);
        $funName = $actionName . self::ACTION_SUFFIX;

        return [$className, $funName];
    }

    /**
     * 解析大版本|模块
     */
    protected function parseModule()
    {
        $this->currModule = self::MODULE_DEFAULT;
        if ($this->app->request->param[self::$module_param]) {
            $this->currModule = $this->app->request->param[self::$module_param];
            $this->currModule = ucfirst(strtolower($this->currModule));
        }
    }

    /**
     * 解析控制器目录
     *
     * @throws \Exception
     */
    protected function parseControlDir()
    {

        $this->currControlDir = self::CONTROL_DIR;
    }


    /**
     * 解析命名空间
     */
    protected function parseNamespace()
    {
        //默认application 不要命名空间前缀
        $tmpVersion = ($this->currVersion == self::VERSIOIN_DEFAULT) ? "" : ($this->currVersion . "\\");
        $this->currNamespace = $tmpVersion . $this->currModule . '\\' . $this->currControlDir . '\\';
    }
}