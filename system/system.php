<?php

class system {
    /*
     * 核心入口
     */
    public function run() {
        session_start();
        $this->registerAutoload();
        $this->path();
        $this->config();
        $this->common();
        $this->route();
    }
    /*
     * 注册自动加载函数
     */
    public function registerAutoload(){
        spl_autoload_register('system::autoloader');
    }
    
    /*
     * 初始化框架的路径
     */
    public function path() {
        define('__ROOT__', dirname(__DIR__));
            define('__APP__', __ROOT__ . '/app/');
                define('__CONTROLLER__', __APP__ . 'controllers/');
                define('__SERVICE__', __APP__ . 'services/');
                define('__MODEL__', __APP__ . 'models/');
                define('__VIEW__', __APP__ . 'views/');
            define('__CONFIG__', __ROOT__ . '/config/');
            define('__SYSTEM__', __ROOT__ . '/system/');
                define('__COMMON__', __SYSTEM__ . 'common/');
                define('__CORE__', __SYSTEM__ . 'core/');
                define('__VENDOR__', __SYSTEM__ . 'vendor/');
            define('__PUBLIC__', __ROOT__ . '/public/');
                define('__JS__', __PUBLIC__ . 'js/');
                define('__CSS__', __PUBLIC__ . 'css/');
                define('__IMAGE__', __PUBLIC__ . 'image/');
    }

    /*
     * 引入config配置文件，存储到全局变量$GLOBALS中
     */
    public function config() {
        $GLOBALS['config'] = include(__CONFIG__ . 'config.php');
        include(__CONFIG__ . 'const.php');
    }

    /*
     * 引入函数库
     */
    public function common() {
        include __COMMON__ . 'function.php';
    }

    /*
     * 路由解析
     */
    public function route() {
        $pathinfo = ltrim($_SERVER['PATH_INFO'] ?? '', '/');
        if(!$pathinfo){
            //默认的控制器方法
            $pathinfo = config('default')['controller'] . '/' . config('default')['action'];
        }
        //获取当前请求的控制器 方法
        list($controller, $action) = explode('/', $pathinfo);
        define('CONTROLLER_NAME', $controller);
        define('ACTION_NAME', $action);
        //拼接控制器命名空间
        $namespace = '\\app\\controllers\\' . ucfirst($controller);
        $class = new $namespace();
        $class->$action();
    }

    public static function autoloader($namespace){
		$namespace = str_replace('\\', '/', $namespace);
        include __ROOT__ . '/' . $namespace . '.php';
    }
}
