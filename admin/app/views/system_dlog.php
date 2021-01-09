<?php include 'header.php';?>
<body>
<table class="layui-table">
	<thead>
		<tr>
	        <td>ID</td>
	        <td>用户</td>
	        <td>IP</td>
	        <td>操作详情</td>
	        <td>操作时间</td>
        </tr>
    </thead>
    <?php
    $types = array(
    	1 => '<i class="layui-icon">&#x1006;</i> <span style="color:#900">用户名错误</span>', 
    	2 => '<i class="layui-icon">&#x1006;</i> <span style="color:#900">密码错误</span>', 
    	3 => '<i class="layui-icon">&#xe605;</i> <span style="color:#090">登录成功</span>'
    );
    $db = db('manager_operate_logs');
    $data = $db->order('operate_id desc')->page(15)->fetchAll();
    foreach($data[0] as $rows){
    ?>
    <tr>
        <td>	
            <?php echo $rows['operate_id'];?>
        </td>
        <td>
            <?php echo $rows['operate_u_name'];?>
        </td>
        <td>
        	<?php echo $rows['operate_ip'];?>
        </td>
        <td>
        	<?php echo $rows['operate_content'];?>
        </td>
        <td>
        	<?php echo date('Y-m-d H:i:s', $rows['operate_time']);?>
        </td>
    </tr>
    <?php
    }
    ?>
</table>
<div>
	<a href="javascript:location.href=location.href;" class="layui-btn"><i class="layui-icon">&#xe666;</i> 刷新数据</a>
	<?php $this->showPager($data[1]);?>
</div>
</body>
</html>