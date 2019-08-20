<?php
namespace app\services;
//use出基类服务层
use system\core\Service;
//use出model类
use app\models\AdminModel;
/*
   定义服务类
*/
class  AdminService extends Service
{
   
   /*
    管理员增加 
   */
  function addadmin()
  {
    //拿到数据
    $name = input('post.adminName'); 
    $password = input('post.password'); 
    $create_at = date('Y-m-d H:i:s');
    $models = new AdminModel;
    $res = $models->add([
        'admin_username'=>$name,
        'admin_password'=>$password,
        'admin_status'=>1,
        'admin_create_at'=>$create_at
    ]);
    if($res)
    {
        return true;
    }
  }
  /*
    管理员查询
  */
 function select()
 {
  //new出model
  $models = new AdminModel;
  $res = $models->select();
  return $res;
 }
  /*
    管理员查询
  */
 function count()
 {
  //new出model
  $models = new AdminModel;
  $res = $models->count();
  return $res;
 }
}