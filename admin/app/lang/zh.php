<?php
return array(
	'APP_NAME'     => '聪狗币管家',
	//主菜单
	'MENU_MAIN'    => array (
		'sys'      =>  array('系统管理'),
	    'coin'      =>  array('炒币操作')
	),
	//子菜单
	'MENU_SON'      =>  array(
		//系统配置
		'sys'       =>  array(
						array('system|index',     '系统信息',       1),
						array('role|index',       '操作员管理',       1),
						array('managers|index',   '系统管理员',                       1),
						array('ccode|index',      '系统功能配置',       1),
						array('members|index',    '用户管理',       1),
						array('config|index',     '系统配置',       1),
						array('system|llog',      '登陆日志',       1),
						array('system|dlog',      '操作日志',       1),
						array('cache|index',      '清空缓存',       1)
					),
		'coin'       =>  array(
                                                                                     array('comments|index',   '收益统计',      1),
						array('articles|index',   '币种参数配置',      1),
						array('test|index',    '火币用户管理',      1),
                                                                                   array('coin_task|index',    '任务管理',      1)

					)
	)
);