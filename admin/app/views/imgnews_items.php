<?php
include 'header.php';
$imgNews = $this->db->where('imgnews_id = ?', array($this->gets[0]))->fetch();
?>
<body>
<div style="line-height:40px;">
	管理子项目 [ <?php echo $imgNews['imgnews_title'];?> ]
	<a href="javascript:history.go(-1);"><i class="layui-icon">&#xe603;</i> 返回列表</a>
</div>
<table class="layui-table">
	<thead>
		<tr>
        	<td>ID / 排序</td>
        	<td>缩略图标</td>
        	<td>链接地址</td>
        	<td>窗口方式</td>
			<td>操作</td>
		</tr>
    </thead>
    <?php
    $dbItems = db('img_news_items');
    $data = $dbItems
    		->where('item_news_id = ?', array($this->gets[0]))
    		->order('item_order asc')
    		->fetchAll();
    foreach($data as $rows){
    ?>
    <tr id="grace-item-<?php echo $rows['item_id'];?>">
		<td>
			<?php echo $rows['item_id'];?> / <?php echo $rows['item_order'];?>
		</td>
		<td>
			<img src="<?php echo sc('pg_static').$rows['item_img_url'];?>" width="50" />
		</td>
		<td>
			<?php echo $rows['item_href'];?>
		</td>
		<td>
			<?php echo $rows['item_target'];?>
		</td>
        <td class="layui-operate-td">
            <a href="<?php echo u(PG_C, 'itemedit', $rows['item_id'].'/'.$this->gets[0]);?>">
            	<i class="layui-icon">&#xe642;</i> 编辑
            </a>
            |
            <a href="javascript:graceDelete('<?php echo u(PG_C, 'itemdelete', $rows['item_id']);?>', '#grace-item-<?php echo $rows['item_id'];?>');">
            	<i class="layui-icon">&#xe640;</i> 删除
            </a>
        </td>
    </tr>
    <?php
    }
    ?>
</table>
<div>
	<a href="<?php echo u(PG_C, 'itemadd', $this->gets[0]);?>" class="layui-btn"><i class="layui-icon">&#xe608;</i> 添加项目</a>
</div>
</body>
</html>