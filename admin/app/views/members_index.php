<?php include 'header.php';?>
<body>
<div style="line-height:40px;">
	<a href="<?php echo u(PG_C, 'add');?>"><i class="layui-icon">&#xe608;</i> 添加</a>
</div>
<table class="layui-table">
	<thead>
		<tr>
        <td>ID</td>
        <td>称呼</td>
        <td>注册时间</td>
        <td>登陆时间</td>
        <td>登陆IP</td>
		</tr>
    </thead>
    <?php
    $data = $this->db
    		->order('u_id desc')
    		->page(12)
    		->fetchAll('u_id, u_nickname, u_status, u_regtime, u_logintime, u_ip');
    foreach($data[0] as $rows){
    ?>
    <tr id="grace-item-<?php echo $rows['u_id'];?>">
		<td>
			<?php echo $rows['u_id'];?>
		</td>
		<td>
			<?php echo $rows['u_nickname'];?>
		</td>
		<td>
			<?php echo date("Y-m-d H:i:s", $rows['u_regtime']);?>
		</td>
		<td>
			<?php echo date("Y-m-d H:i:s", $rows['u_logintime']);?>
		</td>
		<td>
			<?php echo $rows['u_ip'];?>
		</td>
    </tr>
    <?php
    }
    ?>
</table>
<div>
	<a href="javascript:location.href=location.href;" class="layui-btn layui-btn-normal"><i class="layui-icon">&#xe666;</i> 刷新数据</a>
	<?php $this->showPager($data[1]);?>
</div>
</body>
</html>