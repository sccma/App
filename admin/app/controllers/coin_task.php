<?php
/*
power by phpGrace.com - 轻快的实力派! 
*/
class coin_taskController extends graceAdmin{
	
	public $tableName = "hb_task";
	public $tableKey  = "id";
	
	public function __init(){
		parent::__init();
		$this->checkAuth();
	}
	
	public function index(){
		
	}
	
	protected function checkData(){
		$checkRules   = array(
			'id'  => array('int', '', 'id应为 字'),
			'uid'  => array('int', '', '会员id应为 字'),
			'account_id'  => array('int', '', '会员火币账户id应为 字'),
			'access_key'  => array('int', '', '会员火币access_key应为 字'),
			'secret_key'  => array('int', '', '会员火币secret_key应为 字'),
			'cid'  => array('int', '', '币种id应为 字'),
			'invest_type'  => array('int', '', '投资类型应为 字'),
			'first_order_ratio'  => array('int', '', '首单价值应为 字'),
			'stop_profit_ratio'  => array('int', '', '止盈比例(千分比)应为 字'),
			'stop_loss_ratio'  => array('int', '', '止损比例(千分比)应为 字'),
			'state'  => array('int', '', '状态 0:待启用,1:使用中 2暂停应为 字'),
			'created_at'  => array('int', '', '创建时间应为 字'),
			'updated_at'  => array('int', '', '更新时间应为 字'),
			'note'  => array('int', '', '备注应为 字'),
			'cycle'  => array('int', '', '计数周期应为 字'),
			'buy_cash_amount'  => array('int', '', '累计购买总金额应为 字'),
			'buy_amount'  => array('int', '', '累计购买总数量应为 字'),
			'buy_times'  => array('int', '', '累计购买次数(值为负数:禁止系统买卖)应为 字'),
			'sell_times'  => array('int', '', '累计卖出次数应为 字'),
			'first_amount'  => array('int', '', '首单数量应为 字'),
			'first_price'  => array('int', '', '首单价格应为 字'),
			'first_cash_amount'  => array('int', '', '首单金额应为 字'),
			'stop_profit_price'  => array('int', '', '止盈价格应为 字'),
			'stop_loss_price'  => array('int', '', '止损价格应为 字'),
			'processing'  => array('int', '', '是否有订单处于处理中状态(针对火币订单)应为 字'),
			'is_stop_first'  => array('int', '', '是否禁首(0:允许下首单,1:不允许下首单)应为 字'),
			'is_stop_again'  => array('int', '', '是否禁补(0:允许补单,1:不允许补单)应为 字'),
			'tip1'  => array('int', '', '提示1应为 字'),
			'tip2'  => array('int', '', '提示2应为 字'),
			'first_buy_time'  => array('int', '', '首次下单时间应为 字'),
			'last_buy_time'  => array('int', '', '最后购买时间应为 字'),
			'last_sell_time'  => array('int', '', '最后卖出时间应为 字'),
			'last_place_order_time'  => array('int', '', '最后下单时间应为 字'),
			'is_cycle'  => array('int', '', '是否循环 1循环  0不循环应为 字')
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
			if(isset($_POST['file'])){unset($_POST['file']);}
			$this->checkData();
			$res = $this->db->add();
			if($res){
				$this->operateLog('添加 【'.$res.'】');
				$this->json('ok');
			}
			$this->json('添加失败', 'error');
		}
	}
	
	public function edit(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		if(PG_POST){
			if(isset($_POST['file'])){unset($_POST['file']);}
			$this->checkData();
			if($this->db->where($this->tableKey.' = ?', array($this->gets[0]))->update()){
				$this->operateLog('更新 【'.$this->gets[0].'】');
				$this->json('ok');
			}
			$this->json('更新失败', 'error');
		}
	}
	
	public function delete(){
		if(empty($this->gets[0])){$this->json('数据格式错误', 'error');}
		$this->db->where($this->tableKey.' = ?', array($this->gets[0]))->delete();
		$this->operateLog('删除 【'.$this->gets[0].'】');
		$this->json('ok');
	}
	
	//end
}