<?php include 'header.php';?>
<body>
<div style="line-height:38px;">
	<a href="<?php echo u(PG_C, 'add', implode('/', $this->gets));?>"><i class="layui-icon">&#xe608;</i> 添加分类</a>
	&nbsp;&nbsp;&nbsp;<a href="<?php echo u($this->gets[1], 'index');?>">&lt; 返回<?php echo $this->gets[2];?></a>
</div>
<table class="layui-table">
	<thead>
        <td>ID</td>
        <td>类分排序</td>
        <td>分类名称</td>
        <td>左值</td>
        <td>右值</td>
		<th>操作</th>
    </thead>
    <?php
    $tree = new phpGrace\tools\tree($this->gets[0]."_categories", $this->gets);
    $tree->showTableTree(1);
    ?>
</table>
</body>
</html>