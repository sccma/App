<?php include 'header.php';?>
<body>
<div style="line-height:40px;">
	<a href="<?php echo u(PG_C, 'add');?>"><i class="layui-icon">&#xe608;</i> 添加</a>
</div>
<table class="layui-table">
	<thead>
		<tr>
        <td>ID</td>
        <td>分类</td>
        <td>标题</td>
        <td>发布时间</td>
		<td>操作</td>
		</tr>
    </thead>
    <?php
    $topicType = sc('topicType');
    $data = $this->db
    		->order('t_id desc')
    		->page(12)
    		->fetchAll('t_id, t_cate, t_title, t_date');
    foreach($data[0] as $rows){
    ?>
    <tr id="grace-item-<?php echo $rows['t_id'];?>">
		<td>
			<?php echo $rows['t_id'];?>
		</td>
		<td>
			<?php echo $topicType[$rows['t_cate']];?>
		</td>
		<td>
			<?php echo $rows['t_title'];?>
		</td>
		<td>
			<?php echo date('Y-m-d H:i', $rows['t_date']);?>
		</td>
        <td class="layui-operate-td">
            <a href="<?php echo u(PG_C, 'edit', array($rows[$this->tableKey], PG_PAGE));?>">
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
	<a href="javascript:location.href=location.href;" class="layui-btn layui-btn-normal"><i class="layui-icon">&#xe666;</i> 刷新数据</a>
	<?php $this->showPager($data[1]);?>
</div>
</body>
</html>