<?php include 'header.php';?>
<body>
<table class="layui-table">
	<thead>
		<tr>
        <td>ID</td>
        <td>用户名</td>
        <td>登录时间</td>
        <td>登录IP</td>
        <td>登录结果</td>
        </tr>
    </thead>
    <?php
    $types = array(
    	1 => '<i class="layui-icon" style="color:#900">&#x1006;</i> <span style="color:#900">用户名错误</span>', 
    	2 => '<i class="layui-icon" style="color:#900">&#x1006;</i> <span style="color:#900">密码错误</span>', 
    	3 => '<i class="layui-icon" style="color:#090">&#xe605;</i> <span style="color:#090">登录成功</span>'
    );
    $db = db('manager_login_logs');
    $data = $db->order('login_log_id desc')->page(15)->fetchAll();
    foreach($data[0] as $rows){
    ?>
    <tr>
        <td>
            <?php echo $rows['login_log_id'];?>
        </td>
        <td>
            <?php echo $rows['login_log_user'];?>
        </td>
        <td>
        	<?php echo date('Y-m-d H:i:s', $rows['login_log_time']);?>
        </td>
        <td>
        	<?php echo $rows['login_log_ip'];?>
        </td>
        <td>
        	<?php echo $types[$rows['login_log_type']];?>
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