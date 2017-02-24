<?php
namespace app\index\controller;
use think\Controller;  //用于与V层进行数据传递
use think\Request;   //引用Request
use app\common\model\Teacher; //导入教师模型
/**
*教师管理，继承think\Controller后，就可以利用V层对数据进行打包了。
*/

//application/index/controller文件夹下的TeacherController.php文件
class TeacherController extends Controller
{
	public function index()
	{
		$Teacher = new Teacher;
		$teachers = $Teacher->select();
		
		//向V层传数据
		$this->assign('teachers' , $teachers);
		
		//取回打包后的数据
		$htmls = $this->fetch();
		
		//将数据返回给用户
		return $htmls;
	}
	
	public function add()
	{
		$htmls = $this->fetch();
		return $htmls;
	}
	
	/**
	 *插入新数据
	 *@return  html
	 *@author 魔合中国 http://www.3gchinaedu.com
	 *@DateTime 2017-02-21 22:37
	*/
	public function insert()
	{
		//接收传入数据
		$postData = Request::instance()->post();
		
		//实例化Teacher空对象
		$Teacher = new Teacher();
		
		//为对象赋值
		$Teacher->name = $postData['name'];
		$Teacher->username = $postData['username'];
		$Teacher->sex = $postData['sex'];
		$Teacher->email = $postData['email'];
		//$Teacher->create_time = time();
		
		//新增对象至数据表
		$result = $Teacher->validate(true)->save($Teacher->getData());
		
		//反馈结果
		if (false === $result)
		{
			return '新增失败：' . $Teacher->getError();
		} else {
			return '新增成功。新增ID为:' . $Teacher->id;
		}
	}
	
	public function delete()
	{
		//获取get数据
		$id = Request::instance()->param('id/d');
		if (is_null($id) || 0 === $id) {
			return $this->error('未获取到ID信息');
		}
		
		
		//获取要删除的对象
		$Teacher = Teacher::get($id);
	
		//要删除的对象存在
		if(is_null($Teacher)){
			return $this->error('删除失败：' . $id . '的教师，删除失败');
		}
		//删除对象
		if(!$Teacher->delete()){
			return $this->error('删除失败：' . $Teacher->getError());
		}
	
		//进行跳转
		return $this->success('删除成功',url('index'));
	}
	
	//测试专用
	public function test()
	{
		$data = array();
		$data['username'] = 'gn999';
		$data['name']     = 'gn999';
		$data['sex']      = '0';
		$data['email']    = 'hello';
		var_dump($this->validate($data,'Teacher'));
	}
}