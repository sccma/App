<?php include 'header.php';?>
<body>
<div style="line-height:40px;">
	<a href="<?php echo u(PG_C, 'add');?>"><i class="layui-icon">&#xe608;</i> 添加文章</a>
	&nbsp;&nbsp;<a href="<?php echo u('categories', 'index', 'article/'.PG_C.'/'.urlencode('文章管理'));?>"><i class="layui-icon">&#xe60a;</i> 分类管理</a>
	<div id="common-search"><input placeholder="搜索文章" value="<?php echo empty($this->gets[0]) ? '' : $this->gets[0];?>" class="layui-input" type="text"></div>
</div>
<table class="layui-table">
	<thead>
		<tr>
        <td>ID</td>
        <td>文章分类</td>
        <td>文章标题</td>
        <td>展示次数</td>
        <td>创建时间</td>
        <td>展示状态</td>
		<td>操作</td>
		</tr>
    </thead>
    <?php
    $whereSql = array();
    $whereVal = array();
    if(!empty($this->gets[0])){
    	$whereSql[] = 'article_title like ? ';
    	$whereVal[] = '%'.$this->gets[0].'%';
    }
    if(empty($whereSql)){
	    $data = $this->db
	    		->order('a.article_id desc')
	    		->join('as a left join '.sc('db','pre').'article_categories as b on a.article_cate = b.cate_id')
	    		->page(12)
	    		->fetchAll(
	    			'a.article_id, a.article_cate, a.article_title, a.article_img_url, 
	    			a.article_view_number, a.article_create_date, a.article_status, b.cate_name'
	    		);
	}else{
		$data = $this->db
				->where(implode(' and ', $whereSql), $whereVal)
	    		->order('a.article_id desc')
	    		->join('as a left join '.sc('db','pre').'article_categories as b on a.article_cate = b.cate_id')
	    		->page(12)
	    		->fetchAll(
	    			'a.article_id, a.article_cate, a.article_title, a.article_img_url, 
	    			a.article_view_number, a.article_create_date, a.article_status, b.cate_name'
	    		);
	}
    $articleStatus = c('articleStatus');
    $status = array(1 => '<span class="color-green">展示<span>', 2 => '关闭');
    foreach($data[0] as $rows){
    ?>
    <tr id="grace-item-<?php echo $rows['article_id'];?>">
		<td>
			<?php echo $rows['article_id'];?>
		</td>
		<td>
			<?php echo $rows['cate_name'];?>
		</td>
		<td>
			<?php echo $rows['article_title'];?>
		</td>
		<td>
			<?php echo $rows['article_view_number'];?>
		</td>
		<td>
			<?php echo date('Y-m-d H:i', $rows['article_create_date']);?>
		</td>
		<td>
			<?php echo $status[$rows['article_status']];?>
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
	<a href="<?php echo u(PG_C, 'add');?>" class="layui-btn"><i class="layui-icon">&#xe608;</i> 添加文章</a>
	<a href="javascript:location.href=location.href;" class="layui-btn layui-btn-normal"><i class="layui-icon">&#xe666;</i> 刷新数据</a>
	<?php $this->showPager($data[1]);?>
</div>
<script type="text/javascript">
$('#common-search input:eq(0)').keypress(function(e){
	if(e.which == 13){
		var url = "<?php echo u(PG_C, 'index');?>";
		location.href = url + $(this).val();
	}
});
</script>
</body>
</html>