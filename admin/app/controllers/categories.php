<?php
/*
power by phpGrace.com - 轻快的实力派! 
*/
class categoriesController extends graceAdmin{
	
	public $tableKey  = "cate_id";
	
	public function __init(){
		if(empty($this->gets[0])){pgExit('参数错误...');}
		$this->tree = new phpGrace\tools\tree($this->gets[0]."_categories");
		parent::__init();
		$this->checkAuth();
	}
	
	public function index(){
		
	}
	
	protected function checkData(){
		$checkRules   = array(
			'cate_name'  => array('string', '1,100', '分类名称应为1-100个字'),
			'cate_order'  => array('int', '1,10', '分类排序应为整数')
		);
		$checker      = new phpGrace\tools\dataChecker($_POST, $checkRules);
		$checkRes     = $checker->check();
		if(!$checkRes){
			$this->json($checker->error, 'error');
			return false;
		}
	}
	
	public function add(){
		if(PG_POST){
			$this->checkData();
			$res = $this->tree->addNode($_POST['p_id'], $_POST['cate_name'], $_POST['cate_desc'], $_POST['cate_order']);
			$this->operateLog('添加分类 【'.$_POST['cate_name'].'】');
			if($res){
				$this->json('ok');
			}else{
				$this->json('分类添加失败，请注意order不能重复', 'error');
			}
		}
	}
	
	public function edit(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		$parent  = $this->tree->getParentNode($this->gets[3]);
		$this->tree->currentId = $parent['cate_id'];
		if(PG_POST){
			$this->checkData();
			if($_POST['p_id'] != $parent['cate_id']){$this->tree->moveNode($this->gets[3], $_POST['p_id']);}
			//更新新数据
			$up['cate_name']  = $_POST['cate_name'];
			$up['cate_desc'] = empty($_POST['cate_desc']) ? '' : $_POST['cate_desc'];
			$up['cate_order'] = $_POST['cate_order'];
			$this->tree->m->where('cate_id = ?', $this->gets[3])->update($up);
			$this->operateLog('编辑分类 【'.$this->gets[3].'】');
			$this->json('ok');
		}
	}
	
	public function delete(){
		if(empty($this->gets[1])){$this->json('数据格式错误', 'error');}
		$this->tree->delete($this->gets[1]);
		$this->operateLog('删除分类 【'.$this->gets[1].'】');
		$this->json('ok');
	}
	
	//end
}