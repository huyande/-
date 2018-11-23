<?php
/**
*
* 功能说明：文章控制器。
*
**/

namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;
use Vendor\Tree;

class BiaobaiController extends ComController {

	public function lists($p=1){

		$M = M('xcxbb_list');
		$p = intval($p)>0?$p:1;
		$pagesize = 15;
		$offset = $pagesize*($p-1);
		$prefix = C('DB_PREFIX');
		$where['display'] = 0;
		$count = $M->where($where)->count();
		$list = $M->where($where)->join("{$prefix}xcxbb_user ON {$prefix}xcxbb_list.openid = {$prefix}xcxbb_user.openid")->order("{$prefix}xcxbb_list.id DESC")->limit($offset.','.$pagesize)->select();
		$page	=	new \Think\Page($count,$pagesize);
		$page = $page->show();
		$this->assign('page',$page);
		$this->assign('list',$list);
		$this -> display();
	}

	public function del(){

		$where['id'] = I('id');
		$art = M('xcxbb_list')->where($where)->find();
		if($art['photo'] != ''){
			unlink($_SERVER['DOCUMENT_ROOT'].'/'.$art['photo']);
		}
		if(M('xcxbb_list')->where($where)->delete()){
			$w['vid'] = I('id');
			M('xcxbb_guanzhu')->where($w)->delete();
			$this->success('信息删除成功');
		}else{
			$this->error('参数错误');
		}
	}
	
	public function displays(){
		$this -> display();
	}

	public function users(){
		$userList = M('xcxbb_user')->select();
		$this->assign('userList',$userList);
		$this -> display();
	}
	
	public function dsuid(){
		$where['uid'] = I('uid');
		$data['disuid'] = I('d');
		$m = M('xcxbb_user');
		$is = $m->data($data)->where($where)->save();
		if($is){
			$this->success('修改成功');
		}else{
			$this->error('修改失败');
		}
	}
	
}
