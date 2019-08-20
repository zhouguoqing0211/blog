<?php
namespace system\core;
/*
  基类控制器
*/
class Controller{
    /*
       输出一个josn字符串用于数据交互
    */
    public function success($data = [])
    {
        echo json_encode([
            'code' => 0,
            'message' => 'ok',
            'data' => $data
        ]);die;
    }
    /*
        抛出错误信息
    */
    public function error($message = '')
    {
        echo json_encode([
            'code' => 1,
            'message' => $message
        ]);die;
    }
}

