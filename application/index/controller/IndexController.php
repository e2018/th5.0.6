<?php
/* 命名空间，也说明了文件夹所在的文件夹，即以下的文件夹为
app(application)\index(index)\controller(controller)文件夹 */
namespace app\index\controller;
//引用数据库操作类
use think\Db;
//Index既是class类名，也是文件名，说明这个文件的名字为Index.php
class Index
{
    public function index()
    {
		//获取数据表中第一条数据
        var_dump(Db::name('teacher')->find()); 
    }
	
}
