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
		try{
			$Teacher = new Teacher;
			$teachers = $Teacher->select();
		
			//向V层传数据
			$this->assign('teachers' , $teachers);
		
			//取回打包后的数据
			$htmls = $this->fetch();
		
			//将数据返回给用户
			return $htmls;
		} catch (\Exception $e) {
			//由于对异常进行处理，如果发生错误，我们仍然需要查看具体的异常位置及信息，那么需要将以下代码的注释去掉。
			//throw $e;
			return '系统错误' . $e->getMessage();
		}
	}
	
	public function add()
	{
		try{
			$htmls = $this->fetch();
			return $htmls;
		} catch (\Exception $e) {
			return '系统错误' . $e->getMessage();
		}
	}
	
	/**
	 *插入新数据
	 *@return  html
	 *@author 魔合中国 http://www.3gchinaedu.com
	 *@DateTime 2017-02-21 22:37
	*/
	public function insert()
	{
		//提示信息
		$message = '';
		try{
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
				//提示操作成功，并跳转至教师管理列表
				return $this->success('用户' . $Teacher->name . '新增成功。' , url('index'));
			}
		} catch (\Exception $e) {
			//发生异常
			return $e->getMessage();
		}
		
		return $this->error($message);
	}
	
	public function delete()
	{
		try{
			//获取get数据
			$Request = Request::instance();
			$id = Request::instance()->param('id/d');
			
			//判断是否成功接收
			if (0 === $id) {
				throw new \Exception("未获取到ID信息",1);
			}
			
			
			//获取要删除的对象
			$Teacher = Teacher::get($id);
		
			//判断要删除的对象是否存在
			if(is_null($Teacher)){
				throw new \Exception('不存在id为' . $id . '的教师，删除失败', 1);
			}
			
			//删除对象
			if(!$Teacher->delete()){
				$message = '删除失败：' . $Teacher->getError();
			}
			
		//获取到thinkphp内置异常时，直接向上抛出，交给thinkphp处理
		} catch (\think\Exception\HttpResponseException $e) {
			throw $e;
		//获取到正常的异常时，输出异常
		} catch (\Exception $e) {
			return $e->getMessage();
		}
		
		//进行跳转
		return $this->success('删除成功',$Request->header('referer'));
	}
	
	//编辑数据
	public function edit()
	{
		//获取传入ID
		$id = Request::instance()->param('id/d');
		
		//在Teacher表模型中获取当前记录
		if (is_null($Teacher = Teacher::get($id))) {
			return '系统未找到ID为' . $id . '的记录';
		};
		
		//将数据传给V层
		$this->assign('Teacher' , $Teacher);
		
		//获取封装好的V层内容
		$htmls = $this->fetch();
		
		//将封装好的V层内容返回给用户
		return $htmls;
	}
	
	//更新数据
	public function update()
	{
		//接收数据
		$teacher = Request::instance()->post();
		
		//将数据存入Teacher表
		$Teacher = new Teacher();
		$message = '更新成功';
		
		//依据状态定制提示信息
		try{
			if (false === $Teacher->validate(true)->isUpdate()->save($teacher)) {
				$message = '更新失败：' . $Teacher->getMessage();
			}
		} catch (\Exception $e) {
			$message = '更新失败：' . $e->getMessage();
		}
		return $message;
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