<?php

$dsn = "mysql:host=127.0.0.1;";
$db  = new PDO($dsn, 'root', '');
$db->exec("SET NAMES 'utf8'");

echo "--db_user\n";

$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS db_user DEFAULT CHARSET utf8");
$stmt->execute(array());

//////////////////////////////////////////////////////////t_user_login_/////////////////////////////////////////////////
echo "t_user_login_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_user.`t_user_login_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_user.`t_user_login_" . sprintf("%02d", $i) . "` (
  `id`	varchar(254) NOT NULL comment '当id_source为0是手机号，其他为其他平台的id',
  `id_source`	int unsigned NOT NULL DEFAULT 0 comment '0手机注册，1微信，2QQ，3德州扑克id',
  `uid` 	bigint unsigned NOT NULL,
  `password` 	char(32) NULL,
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `deleted`	int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`, `id_source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";
//////////////////////////////////////////////////////////t_user_bind_/////////////////////////////////////////////////
echo "t_user_bind_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_user.`t_user_bind_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_user.`t_user_bind_" . sprintf("%02d", $i) . "` (
  `uid` 	bigint unsigned NOT NULL,
  `id`	varchar(254) NOT NULL comment '当id_source为0是手机号，其他为其他平台的id',
  `id_source`	int unsigned NOT NULL DEFAULT 0 comment '0手机注册，1微信，2QQ',
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `deleted`	int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`, `id`, `id_source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";
//////////////////////////////////////////////////////////t_user_info_/////////////////////////////////////////////////
echo "t_user_info_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_user.`t_user_info_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_user.`t_user_info_" . sprintf("%02d", $i) . "` (
  `uid` 	bigint unsigned NOT NULL,
  `nick`	varchar(254) NOT NULL DEFAULT '' comment 'base64编码，为了兼容emoji',
  `sex`	tinyint unsigned NOT NULL DEFAULT 0 comment '1男，2女',
  `photo_url`	varchar(254) NOT NULL DEFAULT '',
  `cover_url`	varchar(254) NOT NULL DEFAULT '',
  `introduction`	varchar(1024) NOT NULL DEFAULT '' comment 'base64编码，我的介绍',
  `check_status`	tinyint unsigned NOT NULL DEFAULT 0 comment '实名认证审核状态，0未认证，1芝麻认证，2实名认证中，3实名认证通过。实名认证不通过或跳转回初步认证状态',
  `check_msg`	varchar(1024) NOT NULL DEFAULT '' comment '实名认证审核备注',
  `real_name`	varchar(254) NOT NULL DEFAULT '' comment '真实姓名',
  `id_card`	varchar(254) NOT NULL DEFAULT '' comment '身份证号码',
  `id_font`	varchar(254) NOT NULL DEFAULT '' comment '身份证正面',
  `id_back`	varchar(254) NOT NULL DEFAULT '' comment '身份证背面',
  `id_hand`	varchar(254) NOT NULL DEFAULT '' comment '手持身份证',
  `photo_frame_id`	int unsigned NOT NULL DEFAULT 0 comment '头像框ID',
  `photo_frame_expire_time`	int unsigned NOT NULL DEFAULT 0 comment '头像过期时间',
  `completeness`	tinyint unsigned NOT NULL DEFAULT 0 comment '0预注册完成，1领取大圣币，判断首次登录',
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `update_time` int unsigned NOT NULL DEFAULT 0,
  `deleted`	int unsigned NOT NULL DEFAULT 0 comment '被删除时间',
  `is_robot`	tinyint unsigned NOT NULL DEFAULT 0 comment '是否为机器人,0代表否,1代表是',
  `remark`	varchar(254) NOT NULL DEFAULT '',
  `last_token` char(32),
  `withdraw_password` varchar(100) NOT NULL DEFAULT '' COMMENT '提现密码',
  `withdraw_password_error_number` tinyint(1) NOT NULL DEFAULT 0 COMMENT '提现密码的错误次数 目前只能允许错误5次',
  `withdraw_password_local` int(10) NOT NULL DEFAULT 0 COMMENT '重置密码锁定时间，大于0表示被锁定且为锁定时间',
  `is_first_withdraw` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否首次提现 0:否,1:是',
  `is_bind_pay_account` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否已绑定支付账号 0:否,1:是',
  `is_start_robot` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否开启机器人推送，0:否,1:是',
  `platform`  tinyint unsigned NOT NULL DEFAULT 0 comment '1 ios 2 安卓',
  `device_type` 	varchar(254) NOT NULL DEFAULT '',
  `market`  varchar(254) NOT NULL default '' comment '安卓渠道ID或IOS bundle_id',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";

//////////////////////////////////////////////////////////t_user_role_/////////////////////////////////////////////////
echo "t_user_role_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_user.`t_user_role_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_user.`t_user_role_" . sprintf("%02d", $i) . "` (
	`uid` 	bigint unsigned NOT NULL,
	`role_id`	tinyint unsigned NOT NULL DEFAULT 0 comment '1:个人主播，2:主持人，3:解说，4:公会主播，5:PGC主播',
	`create_time` int unsigned NOT NULL DEFAULT 0,
	`deleted`	int unsigned NOT NULL DEFAULT 0,
	 PRIMARY KEY (`uid`, `role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";

$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS db_user_ex DEFAULT CHARSET utf8");
$stmt->execute(array());

//////////////////////////////////////////////////////////t_user_robot/////////////////////////////////////////////////
echo "t_user_robot";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_user.`t_user_robot`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_user.`t_user_robot` (
  `uid`  bigint unsigned NOT NULL DEFAULT 0 comment '用户UID',
  `create_time` int unsigned NOT NULL DEFAULT 0 comment '创建时间',
  `update_time` int unsigned NOT NULL DEFAULT 0 comment '更新时间',
  `deleted` int unsigned NOT NULL DEFAULT 0 comment '软删除字段',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_user_forbidden/////////////////////////////////////////////////
echo "t_user_forbidden";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_user.`t_user_forbidden`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_user.`t_user_forbidden` (
	`uid` 	bigint unsigned NOT NULL,
	`forbidden_type`	int unsigned NOT NULL DEFAULT 0 comment '1屏蔽直播，2禁言，3禁止登录',
	`params`	varchar(254) NOT NULL DEFAULT '',
	`create_time` int unsigned NOT NULL DEFAULT 0,
	 PRIMARY KEY (`uid`, `forbidden_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_user_forbidden_log/////////////////////////////////////////////////
echo "t_user_forbidden_log";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_user.`t_user_forbidden_log`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_user.`t_user_forbidden_log`(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `admin_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '操作管理员ID',
    `uid` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT '被屏蔽的用户ID',
    `operate_alias` char(20) NOT NULL DEFAULT '' COMMENT '屏蔽操作别名',
    `admin_name` char(20) NOT NULL DEFAULT '' COMMENT '操作管理员姓名',
    `operate_content` varchar(50) NOT NULL DEFAULT '' COMMENT '操作的内容',
    `operate_describe` varchar(100) NOT NULL DEFAULT '' COMMENT '操作描述',
    `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
    `deleted` int(10) NOT NULL DEFAULT 0 COMMENT '软删除标识，大于0即删除',
    PRIMARY KEY (`id`),
    KEY `uid_index`(`uid`),
    KEY `operate_alias_index`(`operate_alias`),
    KEY `admin_id_index`(`admin_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户被屏蔽记录表'");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

echo "--db_user_prize\n";
$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS db_user_prize DEFAULT CHARSET utf8");
$stmt->execute(array());
//////////////////////////////////////////////////////////t_user_exchange_data_/////////////////////////////////////////////////
echo "t_user_exchange_data_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_user_ex.`t_user_exchange_data_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_user_ex.`t_user_exchange_data_" . sprintf("%02d", $i) . "` (
  `uid` 	bigint unsigned NOT NULL,
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `create_time_usec` int unsigned NOT NULL DEFAULT 0,
  `zfb_id`	varchar(254) NOT NULL default '' comment '支付宝账号',
  `finish_time` int unsigned NOT NULL DEFAULT 0 comment '审核完成时间',
  `status`	int unsigned NOT NULL DEFAULT 0 comment '0审核中，1审核通过，2审核不通过',
  `reason`	varchar(254) NOT NULL default '' comment '不通过原因',
  `deleted`	int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`, `create_time`, `create_time_usec`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";
//////////////////////////////////////////////////////////t_user_ticket_/////////////////////////////////////////////////
echo "t_user_ticket_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_user_ex.`t_user_ticket_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_user_ex.`t_user_ticket_" . sprintf("%02d", $i) . "` (
  `uid` 	bigint unsigned NOT NULL,
  `ticket_staus` int unsigned NOT NULL DEFAULT 0 comment '0未使用，1已使用，2已过期',
  `ticket_id` bigint unsigned NOT NULL DEFAULT 0,
  `ticket_type` 	int unsigned NOT NULL DEFAULT 0 comment '门票类型，对应表t_ticket',
  `cdkey` varchar(254) NOT NULL default '' comment '兑换码',
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `end_time` int unsigned NOT NULL DEFAULT 0,
  `use_time` int unsigned NOT NULL DEFAULT 0,
  `deleted`	int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`, `ticket_staus`, `ticket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";
//////////////////////////////////////////////////////////t_user_task_/////////////////////////////////////////////////
echo "t_user_task_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_user_ex.`t_user_task_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_user_ex.`t_user_task_" . sprintf("%02d", $i) . "` (
  `uid` 	bigint unsigned NOT NULL,
  `date_time` int unsigned NOT NULL DEFAULT 0 comment '日期，例如20170301',
  `task_type` int unsigned NOT NULL DEFAULT 0,
  `task_step` int unsigned NOT NULL DEFAULT 0 comment '任务完成进度次数，分子',
  `params` text NOT NULL comment 'json结构，根据每个task_type定义不同。例如sec:600',
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `deleted`	int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`, `date_time`, `task_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";
//////////////////////////////////////////////////////////t_receive_task_/////////////////////////////////////////////////
echo "t_receive_task_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_user_ex.`t_receive_task_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_user_ex.`t_receive_task_" . sprintf("%02d", $i) . "` (
  `uid` 	bigint unsigned NOT NULL,
  `date_time` int unsigned NOT NULL DEFAULT 0 comment '日期，例如20170301',
  `task_id` int unsigned NOT NULL DEFAULT 0,
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `deleted`	int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`, `date_time`, `task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";

echo "--db_user_relative\n";
$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS db_user_relative DEFAULT CHARSET utf8");
$stmt->execute(array());
//////////////////////////////////////////////////////////t_attention_/////////////////////////////////////////////////
echo "t_attention_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_user_relative.`t_attention_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_user_relative.`t_attention_" . sprintf("%02d", $i) . "` (
  `uid` 	bigint unsigned NOT NULL,
  `f_uid`	bigint unsigned NOT NULL,
  `initiative` tinyint unsigned NOT NULL DEFAULT 0 comment 'uid关注f_uid',
  `passive` tinyint unsigned NOT NULL DEFAULT 0 comment 'f_uid关注uid',
  `update_time` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`, `f_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";

//////////////////////////////////////////////////////////t_user_black/////////////////////////////////////////////////
echo "t_user_black";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_user_relative.`t_user_black`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_user_relative.`t_user_black` (
  `uid` 	bigint unsigned NOT NULL,
  `black_uid` bigint unsigned NOT NULL DEFAULT 0,
  `create_time` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`, `black_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

echo "--db_user_prize\n";
$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS db_user_prize DEFAULT CHARSET utf8");
$stmt->execute(array());
//////////////////////////////////////////////////////////t_user_prize_msg_/////////////////////////////////////////////////
echo "t_prize_msg_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_user_prize.`t_prize_msg_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_user_prize.`t_prize_msg_" . sprintf("%02d", $i) . "` (
  `uid` 	bigint unsigned NOT NULL,
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `create_time_usec` int unsigned NOT NULL DEFAULT 0,
  `msg`	text NOT NULL,
  `deleted`	int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`, `create_time`, `create_time_usec`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";

echo "--db_config\n";
$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS db_config DEFAULT CHARSET utf8");
$stmt->execute(array());
//////////////////////////////////////////////////////////t_banner/////////////////////////////////////////////////
echo "t_banner";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_config.`t_banner`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_config.`t_banner` (
  `banner_id`	int unsigned NOT NULL auto_increment,
  `banner_type`	int unsigned NOT NULL,
  `sort_id`	int unsigned NOT NULL,
  `name` varchar(254) NOT NULL default '',
  `pic` varchar(1024) NOT NULL default '',
  `url` varchar(1024) NOT NULL default '',
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_ticket/////////////////////////////////////////////////
echo "t_ticket";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_config.`t_ticket`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_config.`t_ticket` (
  `ticket_type`	int unsigned NOT NULL auto_increment,
  `name` varchar(254) NOT NULL default '',
  `pic` varchar(1024) NOT NULL default '',
  `url` varchar(1024) NOT NULL default '',
  PRIMARY KEY (`ticket_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_version/////////////////////////////////////////////////
echo "t_version";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_config.`t_version`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_config.`t_version` (
  `version` 	varchar(254) NOT NULL,
  `platform`	tinyint unsigned NOT NULL DEFAULT 0 comment '1 ios 2 android',
  `market`  varchar(254) NOT NULL default '' comment '安卓渠道ID或IOS bundle_id',
  `new_text` 	varchar(254) NOT NULL,
  `download_url` 	varchar(254) NOT NULL,
  `need_update`	tinyint unsigned NOT NULL DEFAULT 0 comment '1用户选择更新，2强制更新',
  `start_uuid`	bigint unsigned NOT NULL,
  `end_uuid`	bigint unsigned NOT NULL,
  `create_time` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`version`, `platform`, `market`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_commodity/////////////////////////////////////////////////
echo "t_commodity";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_config.`t_commodity`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_config.`t_commodity` (
  `commodity_id` 	int unsigned NOT NULL auto_increment,
  `commodity_name`	varchar(254) NOT NULL,
  `commodity_desc`	varchar(254) NOT NULL,
  `img_url`	varchar(254) NOT NULL,
  `price` 	int unsigned NOT NULL DEFAULT 0 comment '单位分',
  `sort_id` 	int unsigned NOT NULL DEFAULT 0,
  `hidden`	tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`commodity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_commodity_items/////////////////////////////////////////////////
echo "t_commodity_items";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_config.`t_commodity_items`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_config.`t_commodity_items` (
  `commodity_id` 	int unsigned NOT NULL auto_increment,
  `item_id` 	int unsigned NOT NULL DEFAULT 0 comment '1钻石，2大圣币',
  `item_count` 	int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`commodity_id`, `item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_photo_frame_exchange_config/////////////////////////////////////////////////
echo "t_photo_frame_exchange_config";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_config.`t_photo_frame_exchange_config`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_config.`t_photo_frame_exchange_config` (
  `photo_frame_id` 	int unsigned NOT NULL auto_increment,
  `img_url`	varchar(254) NOT NULL,
  `exchange_name`	varchar(254) NOT NULL,
  `exchange_desc`	varchar(254) NOT NULL,
  `create_time` 	int unsigned NOT NULL DEFAULT 0,
  `sort_id` 	int unsigned NOT NULL DEFAULT 0,
  `from_item_id` 	int unsigned NOT NULL,
  `from_item_count` 	int unsigned NOT NULL,
  `to_item_id` 	int unsigned NOT NULL,
  `to_item_count` 	int unsigned NOT NULL,
  `expire_time` 	int unsigned NOT NULL DEFAULT 0 comment '过期秒数',
  `hidden`	tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`photo_frame_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_task_config/////////////////////////////////////////////////
echo "t_task_config";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_config.`t_task_config`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_config.`t_task_config` (
  `task_id` 	int unsigned NOT NULL auto_increment,
  `task_type` 	int unsigned NOT NULL DEFAULT 0 comment '1每日总任务,2启动，3观看，4关注，5发言，6分享，7送礼, 8送指定钻石',
  `task_count` 	int unsigned NOT NULL DEFAULT 1 comment '需要完成的数量',
  `task_params`	text  NOT NULL comment '任务参数，json',
  `img_url`	varchar(254) NOT NULL,
  `task_name`	varchar(254) NOT NULL,
  `task_desc`	varchar(254) NOT NULL,
  `item_id` 	int unsigned NOT NULL DEFAULT 0 comment '参考db_item里的配置',
  `item_params` 	text  NOT NULL comment '道具参数{\"group_id\":1(门票组id),\"compensate_dsb\":20(如果门票不足时补偿的大圣币数)}',
  `item_count` 	int unsigned NOT NULL DEFAULT 0,
  `create_time` 	int unsigned NOT NULL DEFAULT 0,
  `sort_id` 	int unsigned NOT NULL DEFAULT 0,
  `hidden`	tinyint unsigned NOT NULL DEFAULT 0,
  `deleted`	int unsigned NOT NULL DEFAULT 0 comment '被删除时间',
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_gift/////////////////////////////////////////////////
echo "t_gift";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_config.`t_gift`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_config.`t_gift` (
  `gift_id` 	int unsigned NOT NULL auto_increment,
  `gift_type` 	tinyint unsigned NOT NULL DEFAULT 0 comment '直播间类型，1普通直播间',
  `gift_name`	varchar(254) NOT NULL,
  `gift_desc`	varchar(254) NOT NULL,
  `thumb_url`	varchar(254) NOT NULL,
  `img_url`	varchar(254) NOT NULL,
  `animation_url`	varchar(254) NOT NULL,
  `gif_url`	varchar(254) NOT NULL,
  `item_id` 	int unsigned NOT NULL DEFAULT 0,
  `item_count` 	int unsigned NOT NULL DEFAULT 0,
  `combo` 	tinyint unsigned NOT NULL DEFAULT 0 comment '0不可连击，1可连击',
  `sort_id` 	int unsigned NOT NULL DEFAULT 0,
  `hidden`	tinyint unsigned NOT NULL DEFAULT 0 comment '0已激活，1可连击',
  `deleted`	int unsigned NOT NULL DEFAULT 0 comment '被删除时间',
  PRIMARY KEY (`gift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";
/****************** t_special_user *********************/
echo "t_special_user.";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_config.`t_special_user`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
$stmt = $db->prepare("CREATE TABLE db_config.`t_special_user` (
  `type_id` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '主播类型，1：pgc主播，2:线上赛解说',
  `uid` BIGINT UNSIGNED NOT NULL COMMENT '主播uid',
  `priority` INT(10) UNSIGNED NOT NULL DEFAULT 1000 COMMENT '优先级，数值小的优先级高',
  PRIMARY KEY (`type_id`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";
/****************** t_home_live ************************/
echo "t_home_live.";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_config.`t_home_live`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
$stmt = $db->prepare("CREATE TABLE db_config.`t_home_live` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `type_name` VARCHAR(32) NOT NULL COMMENT '分类名,hot:热门直播，game:游戏专区,pgc:精彩节目',
  `limit_num` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '视频数量',
  `index` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序位置',
  `displayName` VARCHAR(32) NOT NULL COMMENT '显示名，热门直播/游戏专区/精彩节目',
  PRIMARY KEY (`id`),
  UNIQUE (`type_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
$stmt = $db->exec("INSERT INTO db_config.`t_home_live` (`id`, `type_name`, `limit_num`, `index`, `displayName`) VALUES
  (1, 'hot', 1, 0, '热门直播'),
  (2, 'game', 2, 1, '游戏专区'),
  (3, 'pgc', 4, 2, '精彩节目');");
echo "\n";

echo "--db_live\n";
$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS db_live DEFAULT CHARSET utf8");
$stmt->execute(array());

/****************** t_global_config *********************/
echo "t_global_config.";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_config.`t_global_config`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
$stmt = $db->prepare("CREATE TABLE db_config.`t_global_config` (
  `g_id`	int unsigned NOT NULL,
  `g_value` varchar(254) NOT NULL default '',
  PRIMARY KEY (`g_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";
//////////////////////////////////////////////////////////t_live_/////////////////////////////////////////////////
echo "t_live_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_live.`t_live_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_live.`t_live_" . sprintf("%02d", $i) . "` (
  `uid`   bigint unsigned NOT NULL DEFAULT 0,
  `stream_id`   varchar(50) NOT NULL DEFAULT '' comment '视频流ID',
  `chatroom_id` varchar(50) NOT NULL DEFAULT '' comment '聊天室ID',
  `live_type` tinyint unsigned NOT NULL DEFAULT 0 comment '直播类型，1:普通直播/normal 2:pk直播/pk 3:线上赛 默认为0',
  `title`   varchar(254) NOT NULL DEFAULT '' comment '视频标题',
  `cover` varchar(254) NOT NULL DEFAULT '' comment '视频封面图',
  `rtmp_push_url` varchar(254) NOT NULL DEFAULT '' comment 'rtmp推流地址',
  `flv_play_url`  varchar(254) NOT NULL DEFAULT '' comment 'flv播放地址',
  `hls_play_url`  varchar(254) NOT NULL DEFAULT '' comment 'his播放地址',
  `rtmp_play_url` varchar(254) NOT NULL DEFAULT '' comment 'rtmp播放地址',
  `hls_playback_url`  varchar(254) NOT NULL DEFAULT '' comment '回放地址',
  `playback_status`  tinyint(1) NOT NULL DEFAULT 0 comment '是否有回放,0:否，1:有',
  `device_type`   varchar(254) NOT NULL DEFAULT '' comment '设备类型',
  `ip`   char(20) NOT NULL DEFAULT '' comment '来源IP',
  `status` tinyint unsigned NOT NULL DEFAULT 0 comment '视频状态，1:直播 2:中断 3:结束',
  `orientation` tinyint unsigned NOT NULL DEFAULT 0 comment '视频是横屏还是竖屏，1:横屏 2:竖屏 默认为0',
  `platform` tinyint unsigned NOT NULL DEFAULT 0 comment '视频来源平台，1:ios, 2:android, 3:pc, 4:obs 默认为0',
  `real_viewer_num` int unsigned NOT NULL DEFAULT 0 comment '真实观众数量',
  `total_viewer_num` int unsigned NOT NULL DEFAULT 0 comment '总的观众数量',
  `realtime_viewer_num` int(11) NOT NULL DEFAULT '0' COMMENT '实时观众数 默认为0',
  `duration` int unsigned NOT NULL DEFAULT 0 comment '视频时长',
  `priority` smallint(6) NOT NULL DEFAULT '1000' COMMENT '视频排序 默认为1000',
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `update_time` int unsigned NOT NULL DEFAULT 0,
  `deleted` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`, `stream_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";

echo "t_live_recording_file";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_live.`t_live_recording_file`");

$stmt = $db->prepare("CREATE TABLE db_live.`t_live_recording_file` (
    `file_size` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '文件大小',
    `duration`  int(10) unsigned NOT NULL DEFAULT 0 comment '推流时长',
    `start_time`   int(10) unsigned NOT NULL DEFAULT 0 comment '分片开始时间',
    `end_time`   int(10) unsigned NOT NULL DEFAULT 0 comment '分片结束时间',
    `file_id` varchar(50) NOT NULL DEFAULT '' COMMENT '文件ID',
    `stream_id`  varchar(254) NOT NULL DEFAULT '' COMMENT '视频流ID',
    `video_id`  varchar(254) NOT NULL DEFAULT '' COMMENT '点播用vid，在点播平台可以唯一定位一个点播视频文件',
    `video_url`  varchar(254) NOT NULL DEFAULT '' COMMENT '点播视频的下载地址',
    `stream_param` text COLLATE utf8_unicode_ci NOT NULL COMMENT '推流URL参数',
    `create_time` int(10) unsigned NOT NULL DEFAULT 0 comment '创建时间',
    `update_time` int(10) unsigned NOT NULL DEFAULT 0 comment '更新时间',
    `deleted` int(10) unsigned NOT NULL DEFAULT 0 comment '软删除字段，大于0即删除',
    index index_stream_id (`stream_id`),
    index index_video_id (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='直播录制文件记录表';");

echo "\n";

echo "t_live_subtitle_file";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_live.`t_live_subtitle_file`");

$stmt = $db->prepare("CREATE TABLE db_live.`t_live_subtitle_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `index` smallint(5) NOT NULL DEFAULT 0 COMMENT '字幕分块 索引',
  `stream_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '视频流ID',
  `file_url` varchar(254) COLLATE utf8_unicode_ci NOT NULL COMMENT '字幕文件路径',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `deleted` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `stream_id_index` (`stream_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='视频字幕文件表';");

//////////////////////////////////////////////////////////t_user_live_info_/////////////////////////////////////////////////
echo "t_user_live_info_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_live.`t_user_live_info_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_live.`t_user_live_info_" . sprintf("%02d", $i) . "` (
  `uid` bigint unsigned NOT NULL DEFAULT 0 comment '用户ID',
  `live_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '直播状态， 0:未直播，1:在直播',
  `video_playback_num` int(11) NOT NULL DEFAULT '0' COMMENT '回放视频总数',
  `total_live_duration` int(11) NOT NULL DEFAULT '0' COMMENT '总直播时长',
  `total_video_num` int(11) NOT NULL DEFAULT '0' COMMENT '总视频数量',
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `update_time` int unsigned NOT NULL DEFAULT 0,
  `deleted` int unsigned NULL DEFAULT 0,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";

//////////////////////////////////////////////////////////t_live_summary/////////////////////////////////////////////////
echo "t_live_summary";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_live.`t_live_summary`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_live.`t_live_summary` (
  `uid`   bigint unsigned NOT NULL DEFAULT 0,
  `stream_id`   varchar(50) NOT NULL DEFAULT '' comment '视频流ID',
  `chatroom_id` varchar(50) NOT NULL DEFAULT '' comment '聊天室ID',
  `live_type` tinyint unsigned NOT NULL DEFAULT 0 comment '直播类型，1:普通直播/normal 2:pk直播/pk 3:线上赛 默认为0',
  `title`   varchar(254) NOT NULL DEFAULT '' comment '视频标题',
  `cover` varchar(254) NOT NULL DEFAULT '' comment '视频封面图',
  `rtmp_push_url` varchar(254) NOT NULL DEFAULT '' comment 'rtmp推流地址',
  `flv_play_url`  varchar(254) NOT NULL DEFAULT '' comment 'flv播放地址',
  `hls_play_url`  varchar(254) NOT NULL DEFAULT '' comment 'his播放地址',
  `rtmp_play_url` varchar(254) NOT NULL DEFAULT '' comment 'rtmp播放地址',
  `hls_playback_url`  varchar(254) NOT NULL DEFAULT '' comment '回放地址',
  `playback_status`  tinyint(1) NOT NULL DEFAULT 0 comment '是否有回放,0:否，1:有',
  `device_type`   varchar(254) NOT NULL DEFAULT '' comment '设备类型',
  `ip`   char(20) NOT NULL DEFAULT '' comment '来源IP',
  `status` tinyint unsigned NOT NULL DEFAULT 0 comment '视频状态，1:直播 2:中断 3:结束',
  `orientation` tinyint unsigned NOT NULL DEFAULT 0 comment '视频是横屏还是竖屏，1:横屏 2:竖屏 默认为0',
  `platform` tinyint unsigned NOT NULL DEFAULT 0 comment '视频来源平台，1:ios, 2:android, 3:pc, 4:obs 默认为0',
  `real_viewer_num` int unsigned NOT NULL DEFAULT 0 comment '真实观众数量',
  `total_viewer_num` int unsigned NOT NULL DEFAULT 0 comment '总的观众数量',
  `realtime_viewer_num` int(11) NOT NULL DEFAULT '0' COMMENT '实时观众数 默认为0',
  `duration` int unsigned NOT NULL DEFAULT 0 comment '视频时长',
  `priority` smallint(6) NOT NULL DEFAULT '1000' COMMENT '视频排序 默认为1000',
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `update_time` int unsigned NOT NULL DEFAULT 0,
  `deleted` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`, `stream_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

echo "--db_recharge\n";
$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS db_recharge DEFAULT CHARSET utf8");
$stmt->execute(array());
//////////////////////////////////////////////////////////t_recharge_/////////////////////////////////////////////////
echo "t_recharge_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_recharge.`t_recharge_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_recharge.`t_recharge_" . sprintf("%02d", $i) . "` (
  `uid` 	bigint unsigned NOT NULL DEFAULT 0 ,
  `source`	int unsigned NOT NULL DEFAULT 0 comment '来源：1支付宝，2微信支付，3IAP',
  `transaction_id` varchar(254) NOT NULL comment '交易号',
  `transaction_time` int unsigned NOT NULL DEFAULT 0,
  `total_price`	int unsigned NOT NULL DEFAULT 0 comment '总金额',
  `notify_data`	text NOT NULL,
  `doubtful` tinyint unsigned NOT NULL DEFAULT 0 comment '0正常，1可疑，2验证失败',
  `doubt_msg`	varchar(254) NOT NULL DEFAULT '' comment '可疑日志',
  `finished` tinyint unsigned NOT NULL DEFAULT 0,
  `create_time` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`, `source`, `transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";

//////////////////////////////////////////////////////////t_recharge_commodity_/////////////////////////////////////////////////
echo "t_recharge_commodity_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_recharge.`t_recharge_commodity_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_recharge.`t_recharge_commodity_" . sprintf("%02d", $i) . "` (
  `uid` 	bigint unsigned NOT NULL DEFAULT 0 ,
  `source`	int unsigned NOT NULL DEFAULT 0 comment '来源：1支付宝，2微信支付，3IAP',
  `transaction_id` varchar(254) NOT NULL comment '交易号',
  `commodity_id` int unsigned NOT NULL DEFAULT 0 comment '商品ID',
  `commodity_count`	int unsigned NOT NULL DEFAULT 0 comment '购买数量',
  `commodity_name`	varchar(254) NOT NULL,
  `commodity_desc`	varchar(254) NOT NULL,
  `img_url`	varchar(254) NOT NULL,
  `price` 	int unsigned NOT NULL DEFAULT 0 comment '单位分',
  PRIMARY KEY (`uid`, `source`, `transaction_id`, `commodity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";

//////////////////////////////////////////////////////////t_recharge_item_/////////////////////////////////////////////////
echo "t_recharge_item_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_recharge.`t_recharge_item_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_recharge.`t_recharge_item_" . sprintf("%02d", $i) . "` (
  `uid` 	bigint unsigned NOT NULL DEFAULT 0 ,
  `source`	int unsigned NOT NULL DEFAULT 0 comment '来源：1支付宝，2微信支付，3IAP',
  `transaction_id` varchar(254) NOT NULL comment '交易号',
  `commodity_id` int unsigned NOT NULL DEFAULT 0 comment '商品ID',
  `item_id` 	int unsigned NOT NULL DEFAULT 0 comment '1钻石，2大圣币',
  `item_count` 	int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`, `source`, `transaction_id`, `commodity_id`, `item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";

echo "--db_item\n";
$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS db_item DEFAULT CHARSET utf8");
$stmt->execute(array());

//////////////////////////////////////////////////////////t_user_item_/////////////////////////////////////////////////
echo "t_user_item_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_item.`t_user_item_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_item.`t_user_item_" . sprintf("%02d", $i) . "` (
  `uid` 	bigint unsigned NOT NULL,
  `item_id` int unsigned NOT NULL DEFAULT 0 comment '道具ID',
  `get_month`	int unsigned NOT NULL DEFAULT 0 comment '获取的月份，例如201706，永久的为0',
  `item_count`	bigint NOT NULL DEFAULT 0 comment '送礼数量',
  `deleted`	int unsigned NOT NULL DEFAULT 0 comment '被删除时间',
  PRIMARY KEY (`uid`, `item_id`, `get_month`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";

//////////////////////////////////////////////////////////t_item_trans_/////////////////////////////////////////////////
echo "t_item_trans_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_item.`t_item_trans_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_item.`t_item_trans_" . sprintf("%02d", $i) . "` (
  `uid` 	bigint unsigned NOT NULL,
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `create_time_usec` int unsigned NOT NULL DEFAULT 0,
  `item_id` int unsigned NOT NULL DEFAULT 0 comment '道具ID',
  `item_count`	bigint NOT NULL DEFAULT 0 comment '送礼数量',
  `trans_before`	bigint unsigned NOT NULL DEFAULT 0 comment '送方送之前剩余数',
  `params` 	text NOT NULL comment 'json结构参数',
  `source`	int unsigned NOT NULL DEFAULT 0 comment '来源：1充值（钻石+） 2送礼（大圣币-或钻石-） 3收礼（蟠桃+） 4钻石转大圣币（钻石-，大圣币+） 5提现（蟠桃-）',
  PRIMARY KEY (`uid`, `create_time`, `create_time_usec`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";

//////////////////////////////////////////////////////////t_gift_send_record_/////////////////////////////////////////////////
echo "t_gift_send_record_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_item.`t_gift_send_record_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_item.`t_gift_send_record_" . sprintf("%02d", $i) . "` (
  `uid` 	bigint unsigned NOT NULL,
  `seq_id`	bigint unsigned NOT NULL comment '送礼ID，同一个combo唯一，生成方式为送非combo或combo第一下的送礼时间的微秒',
  `group_id` 	varchar(254) NOT NULL DEFAULT '' comment '群组ID',
  `gift_id` int unsigned NOT NULL DEFAULT 0,
  `combo`	int unsigned NOT NULL DEFAULT 0 comment 'combo数',
  `receiver_uid` 	bigint unsigned NOT NULL,
  `item_id` int unsigned NOT NULL DEFAULT 0 comment '道具ID',
  `item_count`	bigint NOT NULL DEFAULT 0 comment '道具数量',
  `source`	int unsigned NOT NULL DEFAULT 0 comment '来源：1普通送礼，2投票，3抽奖',
  `create_time` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`, `seq_id`), index index_stream_id (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";

//////////////////////////////////////////////////////////t_gift_recv_record_/////////////////////////////////////////////////
echo "t_gift_recv_record_";
for ($i = 0; $i < 100; ++$i) {
    echo ".";
    $stmt = $db->prepare("DROP TABLE IF EXISTS db_item.`t_gift_recv_record_" . sprintf("%02d", $i) . "`");
    if ($stmt->execute(array()) == false) {
        echo "DROP execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }

    $stmt = $db->prepare("CREATE TABLE db_item.`t_gift_recv_record_" . sprintf("%02d", $i) . "` (
  `uid` 	bigint unsigned NOT NULL,
  `seq_id`	bigint unsigned NOT NULL comment '送礼ID，同一个combo唯一，生成方式为送非combo或combo第一下的送礼时间的微秒',
  `group_id` 	varchar(254) NOT NULL DEFAULT '' comment '群组ID',
  `gift_id` int unsigned NOT NULL DEFAULT 0,
  `combo`	int unsigned NOT NULL DEFAULT 0 comment 'combo数',
  `sender_uid` 	bigint unsigned NOT NULL,
  `item_id` int unsigned NOT NULL DEFAULT 0 comment '道具ID',
  `item_count`	bigint NOT NULL DEFAULT 0 comment '道具数量',
  `source`	int unsigned NOT NULL DEFAULT 0 comment '来源：1普通送礼，2投票，3抽奖',
  `create_time` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`, `seq_id`), index index_stream_id (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    if ($stmt->execute(array()) == false) {
        echo "CREATE execute error!\n";
        print_r($stmt->errorInfo());
        return;
    }
}
echo "\n";

echo "--db_history\n";
$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS db_history DEFAULT CHARSET utf8");
$stmt->execute(array());
//////////////////////////////////////////////////////////t_report/////////////////////////////////////////////////
echo "t_report";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_history.`t_report`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_history.`t_report` (
  `uid`	bigint unsigned NOT NULL,
  `reporter_uid` 	bigint unsigned NOT NULL,
  `report_type`	tinyint unsigned NOT NULL DEFAULT 0,
  `reason` 	varchar(254) NOT NULL DEFAULT '',
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `handled`	tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`, `reporter_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_suggestion/////////////////////////////////////////////////
echo "t_suggestion";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_history.`t_suggestion`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_history.`t_suggestion` (
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `uid`	bigint unsigned NOT NULL,
  `suggestion`	varchar(254) NOT NULL,
  `version` 	varchar(254) NOT NULL,
  `handled`	tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`create_time`, `uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_viewer/////////////////////////////////////////////////
echo "t_viewer";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_history.`t_viewer`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_history.`t_viewer` (
  `id`  int(10) unsigned NOT NULL AUTO_INCREMENT comment '主键ID',
  `uid`  bigint unsigned NOT NULL DEFAULT 0 comment '用户UID',
  `stream_id`   varchar(50) NOT NULL DEFAULT '' comment '视频流ID',
  `is_robot`   tinyint(1) NOT NULL DEFAULT 0 comment '是否是机器人用户，0：否，1：是',
  `create_time` int(10) unsigned NOT NULL DEFAULT 0 comment '创建时间',
  `deleted` int unsigned NOT NULL DEFAULT 0 comment '删除时间',
  PRIMARY KEY (`id`),
  key `uid_index` (`uid`),
  key `stream_id_index` (`stream_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

echo "--db_trailer\n";
$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS db_trailer DEFAULT CHARSET utf8");
$stmt->execute(array());
//////////////////////////////////////////////////////////t_trailer/////////////////////////////////////////////////
echo "t_trailer";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_trailer.`t_trailer`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_trailer.`t_trailer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT comment '主键id',
  `uid` bigint unsigned NOT NULL DEFAULT 0 comment '用户uid',
  `title` varchar(64) NOT NULL DEFAULT '' comment '预告标题，限制15个中文字符内',
  `order_num` int(10) NOT NULL DEFAULT 0 comment '初始预约人数，实际的人数存在redis',
  `time` int(10) unsigned NOT NULL DEFAULT 0 comment '预告开播时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT 0 comment '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT 0 comment '更新时间',
  `deleted` int(10) unsigned NOT NULL DEFAULT 0 comment '删除时间',
  `status` tinyint NOT NULL DEFAULT 0 COMMENT '推送状态：0未推送，1已推送',
  `cover` varchar(128) NOT NULL DEFAULT '' COMMENT '封面图地址',
  PRIMARY KEY (`id`),
  KEY `uid_index` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

echo "--db_game\n";
$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS db_game DEFAULT CHARSET utf8");
$stmt->execute(array());
//////////////////////////////////////////////////////////t_live_vote_show/////////////////////////////////////////////////
echo "t_live_vote_show";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_game.`t_live_vote_show`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_game.`t_live_vote_show` (
  `video_id`   varchar(254) NOT NULL DEFAULT '' comment '视频ID',
  `name` varchar(254) NOT NULL DEFAULT '' comment '节目名称',
  `create_time` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_live_vote/////////////////////////////////////////////////
echo "t_live_vote";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_game.`t_live_vote`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_game.`t_live_vote` (
  `vote_id`	int unsigned NOT NULL auto_increment,
  `video_id`   varchar(254) NOT NULL DEFAULT '' comment '视频ID',
  `name` varchar(254) NOT NULL DEFAULT '' comment '投票名称',
  `duration` int unsigned NOT NULL DEFAULT 0 comment '投票持续秒数，0表示不启动倒计时',
  `dsb_rate`	varchar(254) NOT NULL DEFAULT '' comment '1个大圣币对应多少票比例',
  `diamond_rate`	varchar(254) NOT NULL DEFAULT '' comment '1个钻石对应多少票比例',
  `ans_option_id`	int unsigned NOT NULL DEFAULT 0 comment '答案选项ID 0-3，0表示不设置答案',
  `dsb_limit`	int unsigned NULL DEFAULT 0 comment '限投大圣币',
  `stages` tinyint unsigned NULL DEFAULT 1 comment '投票进程，1未开始，2已开始，3已结束',
  `start_time` int unsigned NULL DEFAULT 0 comment '投票开始时间',
  `end_time` int unsigned NULL DEFAULT 0 comment '投票结束时间',
  `create_time` int unsigned NOT NULL DEFAULT 0 comment '投票创建时间',
  `update_time` int unsigned NOT NULL DEFAULT 0 comment '投票编辑时间',
  `deleted`	tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`vote_id`), index index_video_id (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_live_vote_option/////////////////////////////////////////////////
echo "t_live_vote_option";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_game.`t_live_vote_option`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_game.`t_live_vote_option` (
  `vote_id`	int unsigned NOT NULL DEFAULT 0 comment '投票id',
  `option_id`	int unsigned NOT NULL DEFAULT 0 comment '选项ID 1-3',
  `name` varchar(254) NOT NULL DEFAULT '' comment '选项名称',
  `create_time` int unsigned NOT NULL DEFAULT 0 comment '选项创建时间',
  `update_time` int unsigned NOT NULL DEFAULT 0 comment '选项编辑时间',
  `deleted`	tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`vote_id`, `option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_live_vote_record/////////////////////////////////////////////////
echo "t_live_vote_record";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_game.`t_live_vote_record`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_game.`t_live_vote_record` (
  `id`  int unsigned NOT NULL auto_increment,
  `uid` 	bigint unsigned NOT NULL,
  `vote_id` int unsigned NOT NULL DEFAULT 0 comment '投票id',
  `option_id` int unsigned NOT NULL DEFAULT 0 comment '选项id',
  `combo`	tinyint unsigned NOT NULL DEFAULT 0 comment '1连击，0非连击',
  `combo_count`	int unsigned NOT NULL DEFAULT 1 comment '连击次数',
  `item_id` int unsigned NOT NULL DEFAULT 0 comment '1大圣币，2钻石',
  `item_count` int unsigned NOT NULL DEFAULT 0 comment '大圣币数目或钻石数目',
  `tickets` int unsigned NOT NULL DEFAULT 0 comment '票数',
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `deleted`	tinyint unsigned NOT NULL DEFAULT 0 comment '是否被删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_live_vote_his/////////////////////////////////////////////////
echo "t_live_vote_his";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_game.`t_live_vote_his`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_game.`t_live_vote_his` (
  `vote_id` int unsigned NOT NULL DEFAULT 0 comment '投票id',
  `option_id` int unsigned NOT NULL DEFAULT 0 comment '选项id',
  `ticket_count` int unsigned NOT NULL DEFAULT 0 comment '票数',
  `dsb_count` int unsigned NOT NULL DEFAULT 0 comment '大圣币数',
  `diamond_count` int unsigned NOT NULL DEFAULT 0 comment '钻石数',
  `uid_count` int unsigned NOT NULL DEFAULT 0 comment '人数',
  `uid_rank`	text comment '前3名uid，用|隔开',
  `create_time` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`vote_id`, `option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_live_punish/////////////////////////////////////////////////
echo "t_live_punish";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_game.`t_live_punish`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_game.`t_live_punish` (
  `punish_id`	int unsigned NOT NULL auto_increment,
  `video_id`   varchar(254) NOT NULL DEFAULT '' comment '视频ID',
  `owner_id` int unsigned NOT NULL DEFAULT 0 comment '开启惩罚的用户id',
  `stages` tinyint  NOT NULL DEFAULT 1 comment '惩罚进程，1代表已开始，2代表已结束',
  `name` varchar(254) NOT NULL DEFAULT '' comment '惩罚标题',
  `duration` int unsigned NOT NULL DEFAULT 0 comment '惩罚活动时长',
  `create_time` int unsigned NOT NULL DEFAULT 0 comment '惩罚创建时间',
  `update_time` int unsigned NOT NULL DEFAULT 0 comment '惩罚编辑时间',
  `deleted`	tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`punish_id`), index index_video_id (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_live_punish_record/////////////////////////////////////////////////
echo "t_live_punish_record";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_game.`t_live_punish_record`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_game.`t_live_punish_record` (
  `id`  int unsigned NOT NULL auto_increment,
  `uid` 	bigint unsigned NOT NULL,
  `punish_id` int unsigned NOT NULL DEFAULT 0 comment '惩罚id',
  `gift_id` int unsigned NOT NULL DEFAULT 0 comment '礼物id,1代表急救箱，2代表小刀',
  `combo`	tinyint unsigned NOT NULL DEFAULT 0 comment '1连击，0非连击',
  `combo_count`	int unsigned NOT NULL DEFAULT 1 comment '连击次数',
  `diamond_count` int unsigned NOT NULL DEFAULT 0 comment '钻石数目',
  `create_time` int unsigned NOT NULL DEFAULT 0,
  `deleted`	tinyint unsigned NOT NULL DEFAULT 0 comment '是否被删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////t_live_punish_his/////////////////////////////////////////////////
echo "t_live_punish_his";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_game.`t_live_punish_his`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_game.`t_live_punish_his` (
  `punish_id` int unsigned NOT NULL DEFAULT 0 comment '惩罚id',
  `gift_id` int unsigned NOT NULL DEFAULT 0 comment '礼物id,1代表急救箱，2代表小刀',
  `diamond_count` int unsigned NOT NULL DEFAULT 0 comment '钻石数',
  `uid_count` int unsigned NOT NULL DEFAULT 0 comment '人数',
  `uid_rank`	text comment '前3名uid，用|隔开',
  `create_time` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`punish_id`,`gift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

//////////////////////////////////////////////////////////db_withdraw/////////////////////////////////////////////////
echo "--db_withdraw\n";
$stmt = $db->prepare("CREATE DATABASE IF NOT EXISTS db_withdraw DEFAULT CHARSET utf8");
$stmt->execute(array());

echo "t_pay_account_bind";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_withdraw.`t_pay_account_bind`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_withdraw.`t_pay_account_bind`(
    `uid` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
    `account_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '账号类型，1：支付宝',
    `pay_account` varchar(100) NOT NULL DEFAULT '' COMMENT '支付账号',
    `pay_real_name` varchar(100) NOT NULL DEFAULT '' COMMENT '支付账号真实姓名',
    `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
    `deleted` int(10) NOT NULL DEFAULT 0 COMMENT '软删除标识，大于0即删除',
    PRIMARY KEY (`uid`,`pay_account`,`account_type`, `deleted`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='支付账号绑定表'; ");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

echo "t_withdraw_register";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_withdraw.`t_withdraw_register`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_withdraw.`t_withdraw_register`(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `account_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '账号类型，1：支付宝',
    `audit_status` tinyint(2) unsigned NOT NULL DEFAULT 0 COMMENT '审核状态，0:待审核,1:审核不通过,2:审核通过,3:提现失败,4:提现成功',
    `role_id`   tinyint(2) unsigned NOT NULL DEFAULT 0 comment '主播角色ID，同t_user_role 表一致 ',
    `withdraw_month` tinyint(2) unsigned NOT NULL DEFAULT 0 COMMENT '申请提现的月份',
    `withdraw_year` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT '申请提现的年份',
    `withdraw_money` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '提现金额，默认0 单位分',
    `current_money` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '当前可以提现金额，默认0 单位分',
    `total_peach` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '总的蟠桃数',
    `report_number` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '举报数',
    `shield_number` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '屏蔽数',
    `operation_admin_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '操作后台管理员ID',
    `operation_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '操作时间',
    `mobile_phone` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT '手机号',
    `uid` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
    `pay_real_name` varchar(100) NOT NULL DEFAULT '' COMMENT '支付账号真实姓名',
    `pay_account` varchar(100) NOT NULL DEFAULT '' COMMENT '支付账号',
    `audit_notpass_reason` varchar(50) NOT NULL DEFAULT '' COMMENT '审核不通过原因',
    `withdraw_fail_reason` varchar(50) NOT NULL DEFAULT '' COMMENT '提现失败原因',
    `real_name` varchar(254) NOT NULL DEFAULT '' comment '真实姓名',
    `id_card` varchar(254) NOT NULL DEFAULT '' comment '身份证号码',
    `operation_admin_name` varchar(100) NOT NULL DEFAULT '' COMMENT '操作管理员名称',
    `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
    `deleted` int(10) NOT NULL DEFAULT 0 COMMENT '软删除标识，大于0即删除',
    PRIMARY KEY (`id`),
    KEY `uid_index`(`uid`),
    KEY `role_id_index`(`role_id`),
    KEY `real_name_index`(`real_name`),
    KEY `audit_status_index`(`audit_status`),
    KEY `withdraw_year_index`(`withdraw_year`),
    KEY `withdraw_month_index`(`withdraw_month`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='申请提现记录表';");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

echo "t_withdraw_operation_log";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_withdraw.`t_withdraw_operation_log`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_withdraw.`t_withdraw_operation_log`(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `withdraw_register_id` int(10) NOT NULL DEFAULT 0 COMMENT '提现记录ID，关联t_withdraw_register表主键ID',
    `operation_admin_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '操作后台管理员ID',
    `operation_admin_name` varchar(100) NOT NULL DEFAULT '' COMMENT '操作管理员名称',
    `before_change_status` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '审核状态状态变更前的状态',
    `after_change_status` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '审核状态状态变更后的状态',
    `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
    `deleted` int(10) NOT NULL DEFAULT 0 COMMENT '软删除标识，大于0即删除',
    PRIMARY KEY (`id`),
    KEY `withdraw_register_id_index`(`withdraw_register_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='提现操作日志';");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";

echo "t_gift_statistics";
echo ".";
$stmt = $db->prepare("DROP TABLE IF EXISTS db_withdraw.`t_gift_statistics`");
if ($stmt->execute(array()) == false) {
    echo "DROP execute error!\n";
    print_r($stmt->errorInfo());
    return;
}

$stmt = $db->prepare("CREATE TABLE db_withdraw.`t_gift_statistics`(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `role_id`   tinyint(2) unsigned NOT NULL DEFAULT 0 comment '主播角色ID，同t_user_role 表一致 ',
    `month` tinyint(2) unsigned NOT NULL DEFAULT 0 COMMENT '月份',
    `year` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT '年份',
    `month_start_money` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '月初金额(单位:分)',
    `month_end_money` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '月末金额(单位:分)',
    `current_month_increase_money` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '当月新增金额(单位:分)',
    `current_month_withdraw_money` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '当月提现金额(单位:分)',
    `uid` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT '用户ID',
    `pay_account` varchar(100) NOT NULL DEFAULT '' COMMENT '支付账号',
    `real_name` varchar(254) NOT NULL DEFAULT '' comment '真实姓名 同t_user_info表一致',
    `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
    `deleted` int(10) NOT NULL DEFAULT 0 COMMENT '软删除标识，大于0即删除',
    PRIMARY KEY (`id`),
    KEY `uid_index`(`uid`),
    KEY `role_id_index`(`role_id`),
    KEY `real_name_index`(`real_name`),
    KEY `month_index`(`month`),
    KEY `year_index`(`year`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='主播提现统计表';");
if ($stmt->execute(array()) == false) {
    echo "CREATE execute error!\n";
    print_r($stmt->errorInfo());
    return;
}
echo "\n";
