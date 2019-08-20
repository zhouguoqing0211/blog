<?php
//定义命名空间‘
namespace app\controllers;
//use出机类控制器
use app\controllers\Common;
//use出服务层
use app\services\ArticleService;

class Article extends Common
{
    /*
      资讯管理
    */
    function article_list()
    {
        return view('article_list');
    }
}