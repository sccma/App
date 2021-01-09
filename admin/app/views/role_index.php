<?php include 'header.php';?>
<body>
<table class="layui-table">
	<thead>
		<tr>
        <td>排序</td>
        <td>角色名称</td>
        <td>操作功能</td>
       	</tr>
    </thead>
    <?php
    $this->order = 'role_order asc';
    foreach($this->dataList() as $rows){
    ?>
    <tr id="grace-item-<?php echo $rows['role_id'];?>">
        <td>
            <?php echo $rows['role_order'];?>
        </td>
        <td>
            <?php echo $rows['role_name'];?>
            <input type="hidden" value="<?php echo $rows['role_name'];?>" />
        </td>
        <td class="layui-operate-td">
            <a href="<?php echo u(PG_C, 'edit', $rows[$this->tableKey]);?>">
            	<i class="layui-icon">&#xe642;</i> 编辑
            </a>
            |
            <a href="javascript:graceDelete('<?php echo u(PG_C, 'delete', $rows[$this->tableKey]);?>', '#grace-item-<?php echo $rows['role_id'];?>');">
            	<i class="layui-icon">&#xe640;</i> 删除
            </a>
        </td>
    </tr>
    <?php
    }
    ?>
</table>
<div>
	<a href="<?php echo u(PG_C, 'add');?>" class="layui-btn"><i class="layui-icon">&#xe608;</i> 添加角色</a>
	<a href="javascript:location.href=location.href;" class="layui-btn layui-btn-normal"><i class="layui-icon">&#xe666;</i> 刷新数据</a>
	<?php $this->showPager();?>
</div>
</body>
</html>