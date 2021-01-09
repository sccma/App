<?php include 'header.php';?>
<body>
<div style="line-height:40px;">
	<a href="<?php echo u(PG_C, 'add');?>"><i class="layui-icon">&#xe608;</i> 添加新闻</a>
	&nbsp;&nbsp;<a href="<?php echo u('categories', 'index', 'img_news/'.PG_C.'/'.urlencode('文章列表'));?>"><i class="layui-icon">&#xe60a;</i> 分类管理</a>
</div>
<table class="layui-table">
	<thead>
		<tr>
        <td>ID</td>
        <td>新闻分类</td>
        <td>新闻标题</td>
        <td>浏览次数</td>
        <td>展示状态</td>
		<td>操作</td>
		</tr>
    </thead>
    <?php
    $status = array(1 => '<span class="color-green">展示<span>', 2 => '关闭');
    $data = $this->db
    		->order('a.imgnews_id desc')
    		->join('as a left join '.sc('db','pre').'img_news_categories as b on a.imgnews_cate = b.cate_id')
    		->page(12)
    		->fetchAll('a.*, b.cate_name');
    foreach($data[0] as $rows){
    ?>
    <tr id="grace-item-<?php echo $rows['imgnews_id'];?>">
		<td>
			<?php echo $rows['imgnews_id'];?>
		</td>
		<td>
			<?php echo $rows['cate_name'];?>
		</td>
		<td>
			<?php echo $rows['imgnews_title'];?>
		</td>
		<td>
			<?php echo $rows['imgnews_view_number'];?>
		</td>
		<td>
			<?php echo $status[$rows['imgnews_status']];?>
		</td>
        <td class="layui-operate-td">
            <a href="<?php echo u(PG_C, 'edit', $rows[$this->tableKey]);?>">
            	<i class="layui-icon">&#xe642;</i> 编辑
            </a>
            |
            <a href="<?php echo u(PG_C, 'items', $rows[$this->tableKey].'/'.'page_'.PG_PAGE);?>">
            	<i class="layui-icon">&#xe634;</i> 子项目
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