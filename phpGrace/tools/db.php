<?php
/**
 * mysql 数据库操作类基于pdo
 * @link      http://www.phpGrace.com
 * @copyright Copyright (c) 2010-2018 phpWit.
 * @license   http://www.phpGrace.com/license
 * @package   phpGrace/tools
 * @author    haijun liu mail:5213606@qq.com
 * @version   1.1
 */
namespace phpGrace\tools;
class db{
	public static  $operater;
	public $tableName;
	public $pdo;
	public $sql;
	public $pretreatment;
	public $where;
	public $group;
	public $join;
	public $order;
	public $limit;
	public $eachPage;
	public $conf;
	
	public function __construct($conf){
		if(empty($conf['databaseType'])){$conf['databaseType'] = 'mysql';}
		$this->conf = $conf;
		try{
			switch($this->conf['databaseType']){
				case "sqlsrv":
				$this->pdo  = new \PDO("{$conf['databaseType']}:Server={$conf['host']},{$conf['port']};Database={$conf['dbname']}", $conf['user'], $conf['pwd']);
				break;
				case "mysql":
				$this->pdo  = new \PDO("mysql:host={$conf['host']};port={$conf['port']};dbname={$conf['dbname']}", $conf['user'], $conf['pwd']);
				$this->pdo->query("set names {$conf['charset']}");
				break;				
			}			
		}catch(Exception $e){throw new \pgException('数据库连接失败','请检查数据库相关配置');}
	}
	
	
	public function __clone(){
		$this->sql = null;
		$this->pretreatment = null;
		$this->where = null;
		$this->group = null;
		$this->join = null;
		$this->order = null;
		$this->limit = null;
		$this->eachPage = null;
	}
	
	public static function getInstance($conf, $tableName, $configName){
		$tableName = $conf['pre'].$tableName;
		if(empty(self::$operater[$configName])){
			self::$operater[$configName] = new db($conf);
			self::$operater[$configName]->tableName = $tableName;
			return self::$operater[$configName];
		}
		if(self::$operater[$configName]->tableName == $tableName){return self::$operater[$configName];}
		$cloner = clone self::$operater[$configName];
		$cloner->tableName = $tableName;
		return $cloner;
	}
	
	public function query($sql, $execute = null){
		$this->pretreatment = $this->pdo->prepare($sql);
		return $this->pretreatment->execute($execute);
	}
	public function queryFetch(){
		return $this->pretreatment->fetch(\PDO::FETCH_ASSOC);
	}
	
	public function queryFetchAll(){
		return $this->pretreatment->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	public function add($data = null){
		if(empty($data)){$data = $_POST;}
		if(!is_array($data)){throw new \pgException('插入数据错误','插入数据应为一个一维数组');}
		$this->sql   = "insert into $this->tableName (";
		$fields      = array(); $placeHolder = array(); $insertData  = array();
		foreach ($data as $k => $v){$fields[] = "$k"; $placeHolder[] = "?"; $insertData[] = $v;}
		$this->sql .= implode(', ', $fields).') values ('.implode(', ', $placeHolder).');';
		$this->pretreatment = $this->pdo->prepare($this->sql);
		$this->pretreatment->execute($insertData);
		return $this->pdo->lastInsertId();
	}
	
	public function delete(){
		if(empty($this->where)){throw new \pgException('请使用模型对象的where()函数设置删除条件');}
		$where              = $this->getWhere();
		$this->sql          = "delete from $this->tableName {$where[0]};";
		$this->pretreatment = $this->pdo->prepare($this->sql);
		return $this->pretreatment->execute($where[1]);
	}
	
	public function update($data=null){
		if(is_null($data)){$data = $_POST;}
		if(empty($data) || !is_array($data)){throw new \pgException('update($data) 函数的参数应该为一个一维数组');}
		if(empty($this->where)){throw new pgException('请使用模型对象的 where() 方法设置更新条件');}
		$where = $this->getWhere();
		$this->sql   = "update {$this->tableName} set ";
		$updateData  = array();
    	foreach ($data as $k => $v){$this->sql .= "$k = ?, "; $updateData[] = $v;}
		$this->sql   = substr($this->sql, 0,-2).$where[0].';';
		$updateData  = array_merge($updateData, $where[1]);
		$this->pretreatment = $this->pdo->prepare($this->sql);
		return $this->pretreatment->execute($updateData);
	}
	
	public function field($filedName, $addVal){
		$addVal    = intval($addVal);
		$this->sql = "update {$this->tableName} set {$filedName} = {$filedName} + {$addVal}";
		$where     = $this->getWhere(); $this->sql .= $where[0].';'; return $this->query($this->sql, $where[1]);
	}
	
	public function fetch($fields = null){
		$preArray            = $this->prepare($fields);
		$this->sql           = $preArray[0];
		$this->pretreatment  = $this->pdo->prepare($this->sql);
		$this->pretreatment->execute($preArray[1]);
		return $this->pretreatment->fetch(\PDO::FETCH_ASSOC);
	}
	
	public function fetchAll($fields = null){
		$preArray    = $this->prepare($fields, false);		
		$this->sql   = $preArray[0];
    	if(is_null($this->eachPage)){
			$this->sql .= $this->getLimit().';';
		}else{
			if(empty($this->totalRows)){
	    		$mode         = '/^select .* from (.*)$/Uis';
	    		preg_match($mode, $this->sql, $arr_preg);
				$sql          = 'select count(*) as total from '.$arr_preg['1'];
				if(strpos($sql, 'group by ')){$sql = 'select count(*) as total from ('.$sql.') as witCountTable;';}
	    		$pretreatment = $this->pdo->prepare($sql);
	    		$pretreatment->execute($preArray[1]);
	    		$arrTotal     = $pretreatment->fetch(\PDO::FETCH_ASSOC);
	    		$pager        = new page($arrTotal['total'], $this->eachPage);
			}else{
				$pager        = new page($this->totalRows, $this->eachPage);
			}
    		$this->sql   .= $pager->limit.';';
    	}
		$this->pretreatment  = $this->pdo->prepare($this->sql);
		$this->pretreatment->execute($preArray[1]);
		if(is_null($this->eachPage)){
			return $this->pretreatment->fetchAll(\PDO::FETCH_ASSOC);
		}else{
			$this->eachPage = null;
			return array($this->pretreatment->fetchAll(\PDO::FETCH_ASSOC), $pager);
		}
	}
	
	public function prepare($fields, $limit = true){
		$exeArray = array();
    	$join = $this->getJoin();
    	if(!empty($join)){is_null($fields) ? $sql = 'select * from '.$this->tableName.' '.$join.' ' : $sql = 'select '.$fields.' from '.$this->tableName.' '.$join.' ';}else{is_null($fields) ? $sql = 'select * from '.$this->tableName.' ' : $sql = 'select '.$fields.' from '.$this->tableName.' ';}
    	$where = $this->getWhere();
    	if(!is_null($where)){$sql .= $where[0]; $exeArray = $where[1];}
		$limit ? $sql .= $this->getGroup().$this->getOrder().$this->getLimit().';' : $sql .= $this->getGroup().$this->getOrder();
    	return array($sql,$exeArray);
	}
	
	public function where($where, $array){
		$this->where[0] = $where;
		is_array($array) ? $this->where[1] = $array : $this->where[1] = array($array);
		return $this;
	}
	public function getWhere(){
		if(empty($this->where)){return null;}
		$return = array(' where '.$this->where[0].' ', $this->where[1]);
		$this->where = null;
		return $return;
	}
	
	public function group($group){
		$this->group = $group; return $this;
	}
	
	public function getGroup(){
		if(empty($this->group)){return null;}
		$group = $this->group;
		$this->group = null;
		return ' group by '.$group.' ';
	}
	
	public function order($order){
		$this->order = $order; return $this;
	}
	
	public function getOrder(){
		if(empty($this->order)){return null;}
		$return  = 'order by '.$this->order.' ';
		$this->order = null;
		return $return;
	}
	
	public function join($join_sql){
		$this->join = $join_sql;
		return $this;
	}
	public function getJoin(){
		if(empty($this->join)){return null;}
		$return = $this->join;
		$this->join = null;
		return $return;
	}
	
	public function limit($start, $length){
		$this->limit = array($start, $length);
		return $this;
	}
	
	public function getLimit(){
		if(empty($this->limit)){return null;}
		$return = ' limit '.$this->limit[0].','.$this->limit[1].' ';
		$this->limit = null;
		return $return;
	}
	
	public function page($eachPage = 10, $totalRows = 0){
		$this->eachPage  = $eachPage;
		$this->totalRows = $totalRows;
		return $this;
	}
	
	public function getSql(){
		return $this->sql;
	}
	
	public function error(){
		$error = is_null($this->pretreatment) ? $this->pdo_obj->errorInfo() : $this->pretreatment->errorInfo();
		if(isset($error[2])){return $error[2];}
		return null;
	}
	
	public function rowCount(){
		if(empty($this->pretreatment)){return null;}
		return $this->pretreatment->rowCount();
	}
	
	public function lastInsertId(){
		return $this->pdo->lastInsertId();
	}
	
    public function getDb(){
    	return $this->pdo;
    }
    
	public function count(){
		$this->sql = "select count(*) as total from $this->tableName ";
		$where = $this->getWhere(); $this->sql.= $where[0].';';
		$this->query($this->sql, $where[1]);
		$return = $this->pretreatment->fetch();
		if(empty($return['total'])){return 0;}
		return $return['total'];
	}
	
	public function max($field){
		$this->sql = "select max($field) as max from $this->tableName";
		$where = $this->getWhere(); $this->sql .= $where[0].';';
		$this->query($this->sql, $where[1]);
		$return = $this->pretreatment->fetch();
		if(empty($return['max'])){return 0;}
		return $return['max'];
	}
	
	public function min($field){
		$this->sql = "select min($field) as min from $this->tableName";
		$where = $this->getWhere(); $this->sql .= $where[0].';';
		$this->query($this->sql, $where[1]);
		$retutn = $this->pretreatment->fetch();
		if(empty($retutn['min'])){return 0;}
		return $retutn['min'];
	}
	
	public function avg($field){
		$this->sql = "select avg($field) as avg from $this->tableName";
		$where = $this->getWhere();
		$this->sql .= $where[0].';';
		$this->query($this->sql, $where[1]);
		$return = $this->pretreatment->fetch();
		if(empty($return['avg'])){return 0;}
		return $return['avg'];
	}
	
	public function sum($field){
		$this->sql = "select sum($field) as sum from $this->tableName";
		$where = $this->getWhere(); $this->sql .= $where[0].';';
		$this->query($this->sql, $where[1]);
		$return = $this->pretreatment->fetch();
		if(empty($return['sum'])){return 0;}
		return $return['sum'];
	}
	
	public function mysqlV(){
		$this->query('select version();');
		$return = $this->pretreatment->fetch();
		return $return[0];
	}
	
	public function desc(){
		$this->query('select ORDINAL_POSITION,COLUMN_NAME ,DATA_TYPE, COLUMN_COMMENT  
		from information_schema.columns where table_schema = ? and table_name = ? 
		order by ORDINAL_POSITION asc;', array($this->conf['dbname'], $this->tableName));
		return $this->queryFetchAll();
	}
	
	public function beginTransaction(){
		$this->pdo->beginTransaction();
	}
	
	public function commit(){
		$this->pdo->commit();
	}
	
	public function rollback(){
		$this->pdo->rollback();
	}
	
	public function debugSql(){
		echo '<script>console.log("phpGrace log : sql 命令 : '.$this->sql.'");</script>';
	}
	
}