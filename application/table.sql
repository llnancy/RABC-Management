CREATE TABLE `admin` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`username`  varchar(16) NOT NULL COMMENT '用户名' ,
`password`  varchar(50) NOT NULL COMMENT '密码' ,
`true_name`  varchar(10) NOT NULL COMMENT '真实姓名' ,
`avatar`  varchar(64) NOT NULL DEFAULT '__ADMINSTATIC__/avatar/default.jpg' COMMENT '管理员头像',
`add_time`  int(10) NOT NULL COMMENT '添加时间,存入int型时间戳' ,
`rid`  int(10) NOT NULL COMMENT '所属角色ID' ,
`status`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：0：禁用，1：正常' ,
PRIMARY KEY (`id`)
)
;