<?php
class ccodeController extends graceAdmin{
	
	public function __init(){
		parent::__init();
		$this->checkAuth();
	}
	
	public function index(){
		
	}
	
	public function add(){
		if(empty($_POST['name'])){pgExit();}
		if(is_file(PG_PATH.'/'.PG_CONTROLLER.'/'.$_POST['name'].'.php')){
			$this->json('控制器文件已存在', 'error');
		}
		$fieldsName      = explode('--graceSplitor--', $_POST['fieldsName']);
		array_pop($fieldsName);
		$fieldsZh        = explode('--graceSplitor--', $_POST['fieldsZh']);
		$fieldsCheckType = explode('--graceSplitor--', $_POST['fieldsCheckType']);
		$fieldsCheckRole = explode('--graceSplitor--', $_POST['fieldsCheckRole']);
		$fieldsErrMsg    = explode('--graceSplitor--', $_POST['fieldsErrMsg']);
		$fieldsIsShow    = explode('--graceSplitor--', $_POST['fieldsIsShow']);
		$fieldsIsMust    = explode('--graceSplitor--', $_POST['fieldsIsMust']);
		//创建控制器
		$strC = file_get_contents('ccodeTpl/controller.php');
		$graceCheckRules = array();
		foreach($fieldsName as $k => $v){
			if(!empty($fieldsErrMsg[$k])){
				$graceCheckRules[] = "
			'{$v}'  => array('{$fieldsCheckType[$k]}', '{$fieldsCheckRole[$k]}', '{$fieldsErrMsg[$k]}')";
			}
		}
		$replace = array(
			'graceTmpController' => $_POST['name'].'Controller',
			'GraceTableName'     => $_POST['tablename'],
			'GraceTableKey'      => $_POST['tablekey'],
			'graceCheckRules'    => implode(',',$graceCheckRules)
		);
		foreach($replace as $k => $v){
			$strC = str_replace($k, $v, $strC);
		}
		//根据配置情况删减方法
		$funs = explode('-', $_POST['funs']);
		if($funs[0] == 0){
			$mode = '/public function add\(.*}.*public/Uis';
			$strC = preg_replace($mode, 'public', $strC);
			$graceAddBtn = '';
		}else{
			$graceAddBtn='	<a href="<?php echo u(PG_C, \'add\');?>" class="layui-btn"><i class="layui-icon">&#xe608;</i> 添加</a>';
		}
		if($funs[1] == 0){
			$mode = '/public function edit\(.*}.*public/Uis';
			$strC = preg_replace($mode, 'public', $strC);
		}
		if($funs[2] == 0){
			$mode = '/public function delete\(.*}.*end/Uis';
			$strC = preg_replace($mode, '', $strC);
		}
		file_put_contents(PG_PATH.'/'.PG_CONTROLLER.'/'.$_POST['name'].'.php', $strC);
		
		//创建视图 - 列表
		$graceTh = '';
		$graceTds = '';
		$fieldsSelect = array();
		foreach($fieldsName as $k => $v){
			if($fieldsIsShow[$k] == 1){
				$fieldsSelect[] = $v;
				$graceTh .= '
        <td>'.$fieldsZh[$k].'</td>';
				$graceTds .='
		<td>
			<?php echo $rows[\''.$v.'\'];?>
		</td>';
			}
		}
		$strIndex = file_get_contents('ccodeTpl/list.php');
		$replace = array(
			'graceAddBtn'    => $graceAddBtn,
			'graceTh'        => $graceTh,
			'graceTds'       => $graceTds,
			'graceFields'    => implode(', ', $fieldsSelect),
			'graceOrder'     => $_POST['tablekey'].' desc',
			'graceTableKey'  => $_POST['tablekey']
		);
		foreach($replace as $k => $v){
			$strIndex = str_replace($k, $v, $strIndex);
		}
		file_put_contents(PG_PATH.'/'.PG_VIEW.'/'.$_POST['name'].'_index.php', $strIndex);
		//创建 add 视图
		if($funs[0] == 1){
			$strAdd = file_get_contents('ccodeTpl/add.php');
			$graceItems = '';
			foreach($fieldsName as $k => $v){
				if($v != $_POST['tablekey'] && $fieldsIsMust[$k] == 1){
					if(!empty($fieldsErrMsg[$k])){
						$graceItems .= '
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">'.$fieldsZh[$k].'</label>
		<div class="layui-input-block">
			<input type="text" name="'.$v.'" class="layui-input" checkType="'.$fieldsCheckType[$k].'" checkData="'.$fieldsCheckRole[$k].'" checkMsg="'.$fieldsErrMsg[$k].'" />
		</div>
	</div>
</div>';
					}else{
						$graceItems .= '
<div class="layui-form-item">
	<div class="layui-inline">
		<label class="layui-form-label">'.$fieldsZh[$k].'</label>
		<div class="layui-input-block">
			<input type="text" name="'.$v.'" class="layui-input" />
		</div>
	</div>
</div>';
					}
				}
			}
			$strAdd = str_replace('graceItems', $graceItems, $strAdd);
			file_put_contents(PG_PATH.'/'.PG_VIEW.'/'.$_POST['name'].'_add.php', $strAdd);
		}
		
		//更新 edit 视图
		if($funs[1] == 1){
			$strEdit = file_get_contents('ccodeTpl/edit.php');
			$strEdit = str_replace('graceItems', $graceItems, $strEdit);
			file_put_contents(PG_PATH.'/'.PG_VIEW.'/'.$_POST['name'].'_edit.php', $strEdit);
		}
		
		$this->json('ok');
	}
	
	public function fields(){
		if(empty($this->gets[0])){$this->json('参数错误', 'error');}
		$db  = db($this->gets[0]);
		$pdo = $db->pdo;
		$sta = $pdo->prepare(
					'select ORDINAL_POSITION,COLUMN_NAME ,DATA_TYPE, COLUMN_COMMENT  
					from information_schema.columns where table_schema = ? and table_name = ? 
					order by ORDINAL_POSITION asc');
		$sta->execute(array(sc('db','dbname'), sc('db','pre').$this->gets[0]));
		$fields = $sta->fetchAll(\PDO::FETCH_ASSOC);
		if(empty($fields)){
			$this->json('数据表名称错误', 'error');
		}
		$str = '';
		$checkType = '<select>
			<option value="int">int</option>
			<option value="string">string</option>
			<option value="between">between</option>
			<option value="betweenD">betweenD</option>
			<option value="betweenF">betweenF</option>
			<option value="same">same</option>
			<option value="sameWith">sameWith</option>
			<option value="notSame">notSame</option>
			<option value="email">email</option>
			<option value="phone">phone</option>
			<option value="zipCode">zipCode</option>
			<option value="reg">reg</option>
			<option value="fun">fun</option>
		</select>';
		foreach($fields as $field){
			$str .= '<tr>
					<td>'.$field['COLUMN_NAME'].'</td>
					<td><input type="text" value="'.$field['COLUMN_COMMENT'].'" class="layui-input" /></td>
					<td>'.$checkType.'</td>
					<td><input type="text" class="layui-input" /></td>
					<td><input type="text" class="layui-input" value="'.$field['COLUMN_COMMENT'].'应为 字" /></td>
					<td><input value="1" title="展示" type="checkbox" checked="checked" lay-skin="primary" /></td>
					<td><input value="1" title="必填" type="checkbox" checked="checked" lay-skin="primary" /></td>
				</tr>';
		}
		$this->json($str, 'ok');
	}
}