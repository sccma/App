<?php include 'header.php';?>
<body>
<div style="line-height:40px;">
	<a href="<?php echo u(PG_C, 'add');?>"><i class="layui-icon">&#xe608;</i> 添加</a>
</div>
<table class="layui-table">
	<thead>
		<tr>
        <td>id</td>
        <td>会员id</td>
        <td>会员火币账户id</td>
        <td>会员火币access_key</td>
        <td>会员火币secret_key</td>
        <td>币种id</td>
        <td>投资类型</td>
        <td>首单价值</td>
        <td>止盈比例(千分比)</td>
        <td>止损比例(千分比)</td>
        <td>状态 0:待启用,1:使用中 2暂停</td>
        <td>创建时间</td>
        <td>更新时间</td>
        <td>备注</td>
        <td>计数周期</td>
        <td>累计购买总金额</td>
        <td>累计购买总数量</td>
        <td>累计购买次数(值为负数:禁止系统买卖)</td>
        <td>累计卖出次数</td>
        <td>首单数量</td>
        <td>首单价格</td>
        <td>首单金额</td>
        <td>止盈价格</td>
        <td>止损价格</td>
        <td>是否有订单处于处理中状态(针对火币订单)</td>
        <td>是否禁首(0:允许下首单,1:不允许下首单)</td>
        <td>是否禁补(0:允许补单,1:不允许补单)</td>
        <td>首次下单时间</td>
        <td>最后购买时间</td>
        <td>最后卖出时间</td>
        <td>最后下单时间</td>
        <td>是否循环 1循环  0不循环</td>
		<td>操作</td>
		</tr>
    </thead>
    <?php
    $data = $this->db
    		->order('id desc')
    		->page(12)
    		->fetchAll('id, uid, account_id, access_key, secret_key, cid, invest_type, first_order_ratio, stop_profit_ratio, stop_loss_ratio, state, created_at, updated_at, note, cycle, buy_cash_amount, buy_amount, buy_times, sell_times, first_amount, first_price, first_cash_amount, stop_profit_price, stop_loss_price, processing, is_stop_first, is_stop_again, first_buy_time, last_buy_time, last_sell_time, last_place_order_time, is_cycle');
    foreach($data[0] as $rows){
    ?>
    <tr id="grace-item-<?php echo $rows['id'];?>">
		<td>
			<?php echo $rows['id'];?>
		</td>
		<td>
			<?php echo $rows['uid'];?>
		</td>
		<td>
			<?php echo $rows['account_id'];?>
		</td>
		<td>
			<?php echo $rows['access_key'];?>
		</td>
		<td>
			<?php echo $rows['secret_key'];?>
		</td>
		<td>
			<?php echo $rows['cid'];?>
		</td>
		<td>
			<?php echo $rows['invest_type'];?>
		</td>
		<td>
			<?php echo $rows['first_order_ratio'];?>
		</td>
		<td>
			<?php echo $rows['stop_profit_ratio'];?>
		</td>
		<td>
			<?php echo $rows['stop_loss_ratio'];?>
		</td>
		<td>
			<?php echo $rows['state'];?>
		</td>
		<td>
			<?php echo $rows['created_at'];?>
		</td>
		<td>
			<?php echo $rows['updated_at'];?>
		</td>
		<td>
			<?php echo $rows['note'];?>
		</td>
		<td>
			<?php echo $rows['cycle'];?>
		</td>
		<td>
			<?php echo $rows['buy_cash_amount'];?>
		</td>
		<td>
			<?php echo $rows['buy_amount'];?>
		</td>
		<td>
			<?php echo $rows['buy_times'];?>
		</td>
		<td>
			<?php echo $rows['sell_times'];?>
		</td>
		<td>
			<?php echo $rows['first_amount'];?>
		</td>
		<td>
			<?php echo $rows['first_price'];?>
		</td>
		<td>
			<?php echo $rows['first_cash_amount'];?>
		</td>
		<td>
			<?php echo $rows['stop_profit_price'];?>
		</td>
		<td>
			<?php echo $rows['stop_loss_price'];?>
		</td>
		<td>
			<?php echo $rows['processing'];?>
		</td>
		<td>
			<?php echo $rows['is_stop_first'];?>
		</td>
		<td>
			<?php echo $rows['is_stop_again'];?>
		</td>
		<td>
			<?php echo $rows['first_buy_time'];?>
		</td>
		<td>
			<?php echo $rows['last_buy_time'];?>
		</td>
		<td>
			<?php echo $rows['last_sell_time'];?>
		</td>
		<td>
			<?php echo $rows['last_place_order_time'];?>
		</td>
		<td>
			<?php echo $rows['is_cycle'];?>
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