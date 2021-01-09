<?php include 'header.php';?>
<body>
<table class="layui-table">
	<thead>
		<tr>
        <td>ID</td>
        <td>头像</td>
        <td>用户名</td>
        <td>角色归属</td>
        <td>用户呼称</td>
        <td>登录IP</td>
        <td>登录时间</td>
		<td>操作</td>
		</tr>
    </thead>
    <?php
    $data = $this->db
    		->order('a.manager_id desc')
    		->join('as a left join '.sc('db','pre').'manager_roles as b on a.manager_role_id = b.role_id')
    		->page(20)
    		->fetchAll('a.*, b.role_name');
    foreach($data[0] as $rows){
    ?>
    <tr id="grace-item-<?php echo $rows['manager_id'];?>">
		<td>
			<?php echo $rows['manager_id'];?>
		</td>
		<td>
			<img src="<?php echo PG_SROOT.$rows['manager_face'];?>" width="30" />
		</td>
		<td>
			<?php echo $rows['manager_username'];?>
		</td>
		<td>
			<?php echo $rows['role_name'];?>
		</td>
		<td>
			<?php echo $rows['manager_nickname'];?>
		</td>
		<td>
			<?php echo $rows['manager_ip'];?>
		</td>
		<td>
			<?php echo date('Y-m-d H:i:s', $rows['manager_login_time']);?>
		</td>
        <td class="layui-operate-td">
            <a href="<?php echo u(PG_C, 'edit', $rows[$this->tableKey]);?>">
            	<i class="layui-icon">&#xe642;</i> 编辑
            </a>
            |
            <a href="javascript:graceDelete('<?php echo u(PG_C, 'delete', $rows[$this->tableKey]);?>', '#grace-item-<?php echo $rows[$this->tableKey];?>');">
            	<i class="layui-icon">&#xe640;</i> 删除
            </a>
        </td>
    </tr>
    <?php
    }
    ?>
</table>
<div>
	<a href="<?php echo u(PG_C, 'add');?>" class="layui-btn"><i class="layui-icon">&#xe608;</i> 添加管理员</a>
	<a href="javascript:location.href=location.href;" class="layui-btn layui-btn-normal"><i class="layui-icon">&#xe666;</i> 刷新数据</a>
	<?php $this->showPager($data[1]);?>
</div>
</body>
</html>