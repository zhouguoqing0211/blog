<?php
//定义命名空间‘
namespace app\controllers;
//use出机类控制器
use app\controllers\Common;
//use出服务层
use app\services\AlbumService;

class Album extends Common
{
    /*
      图片管理
    */
    function article_list()
    {
        return view('picture_list');
    }
}