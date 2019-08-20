<?php
namespace system\core;
//系统服务类
class Service
{
    var $error = '';
    //抛出错误信息的方法
    protected function error($message = '')
    {
        
        //给错误属性进行赋值
        $this->error = $message;
        //
        return false;
    }
}
