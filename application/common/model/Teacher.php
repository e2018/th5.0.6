<?php
/*简单的原理重复记：namespace说明了文件位于
application\common\model文件夹中
*/
namespace app\common\model;
//导入think\Model类
use think\Model;
/**
*Teacher教师表
*/

//我的类名叫做Teacher，对应的文件名为Teacher.php，
//该类继承了Model类，Model我们在文件头中，提前使用use进行了导入。
class Teacher extends Model
{
	
}