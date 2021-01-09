<?php include 'header.php';?>
<body>
<div style="line-height:40px;">
	<a href="<?php echo u(PG_C, 'add');?>"><i class="layui-icon">&#xe608;</i> 添加</a>
</div>
<table class="layui-table">
	<thead>
		<tr>
        <td></td>
        <td>姓名</td>
		<td>操作</td>
		</tr>
    </thead>
    <?php
    $data = $this->db
    		->order('id desc')
    		->page(12)
    		->fetchAll('id, name');
    foreach($data[0] as $rows){
    ?>
    <tr id="grace-item-<?php echo $rows['id'];?>">
		<td>
			<?php echo $rows['id'];?>
		</td>
		<td>
			<?php echo $rows['name'];?>
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
	<a href="<?php echo u(PG_C, 'add');?>" class="layui-btn"><i class="layui-icon">&#xe608;</i> 添加</a>
	<a href="javascript:location.href=location.href;" class="layui-btn layui-btn-normal"><i class="layui-icon">&#xe666;</i> 刷新数据</a>
	<?php $this->showPager($data[1]);?>
</div>
</body>
</html>