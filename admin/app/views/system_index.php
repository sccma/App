<?php
include 'header.php';
$info = phpGrace\tools\server::info();
?>
<body>
<table class="layui-table">
	<colgroup>
		<col width="150">
		<col>
		</colgroup>
	<thead>
		<tr>
			<td>相关配置</td>
			<td>详细信息</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>操作系统</td>
			<td><?php echo $info[0];?></td>
		</tr>
		<tr>
			<td>
				php版本
			</td>
			<td>
				<?php echo $info[1];?>
			</td>
		</tr>
		<tr>
			<td>
				默认时区
			</td>
			<td>
				<?php echo $info[2];?>
			</td>
		</tr>
		<tr>
			<td>
				最大上传
			</td>
			<td>
				<?php echo $info[3];?>
			</td>
		</tr>
		<tr>
			<td>
				最大运行时间
			</td>
			<td>
				<?php echo $info[4];?>
			</td>
		</tr>
		 <tr>
			<td>
				运行模式
			</td>
			<td>
				<?php echo $info[5];?>
			</td>
		</tr>
		<tr>
			<td>
				GD 库信息
			</td>
			<td>
				<?php echo $info[6];?>
			</td>
		</tr>
		<tr>
			<td>
				系统版本
			</td>
			<td>
                聪狗币管家 <sup style="font-size:8px; color:#900;">1.1.1</sup>
			</td>
		</tr>
	</tbody>
</table>
</body>
</html>