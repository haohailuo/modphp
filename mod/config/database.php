<?php
/**
 * 设置数据库结构
 * 数组第一维的键名表示数据表名，只能用小写英文，在系统安装时会自动添加前缀
 * 数组第二维为字段和属性的键值对，字段名采用下划线命名法命名并以表名作为前缀(外键除外)
 * 为数组添加/删除/更改一维键值对将会添加/删除/更改数据表，添加/删除/更改二维键值对将会添加/删除/更改数据表字段
 * 如果一张表中出现与其他表中相同的字段名，并且该字段是另一张表的主键，那么这个字段将作为本表的外键
 * 只有合法的字段的数据才会被插入到数据库，而其他数据将会被过滤
 * 数据表/模块说明：
 * 1. 数据表即模块。ModPHP 的每一张数据表都是一个独立的模块:
 * 	  在 classes/ 目录有独立的 {module}.class.php 模块类文件
 * 	  可以在 config.php 配置文件中(或使用 config())为该模块设置相关的配置
 * 2. 主键即 id。
 * 	  ModPHP 在添加数据库记录后使用 id 查询新插入的记录并将其作为单条记录返回
 * 	  在添加或更新记录时都自动过滤主键/id
 * 	  在更新或删除记录时，默认使用主键/id进行索引, 在获取单记录时也默认使用主键/id索引
 * 3. 在查询多组记录时，可以使用所有字段进行查询，并支持这些额外的参数:
 * 	  orderby:  按设置的字段进行排序
 * 	  sequence: 排序方式: asc(升序)、desc(降序)、rand(随机)
 * 	  limit:    单页获取记录上限
 * 	  page:     当前页面数码
 * 	  keyword:  搜索记录(模糊查询)时可用
 * 4. 特殊字段: 
 *    {module}_id:     主键字段
 *    {module}_parent: 表示 id = {module}_parent 的记录为当前记录的父记录
 *    {module}_time:   时间字段，将自动填充一个 UNIX 时间戳
 *    {module}_link:   链接字段，当访问设置的 URL 链接时将调用该模块所对应的模板，而不再对链接进行解析
 *    {ex-table}_id:   外键(从表主键)字段，用于将两个表关联
 * 5. 系统将自动注册这些模块函数:
 * 	  _{module}():           包含实例化对象、绑定的 Api Hook、当前分页、总页数等记录相关信息的函数，如 _user()
 * 	  get_{module}():        获取单条记录的函数，如 get_user()
 * 	  get_multi_{module}():  获取多条记录的函数，如 get_multi_user()
 * 	  get_search_{module}(): 搜索(模糊查询)多条记录的函数，如 get_search_user()
 * 	  the_{module}():        存储当前记录信息的函数，如 the_user()
 * 	  {module}_*():          与数据表字段名对应的函数，如 user_id(), user_name(), user_nickname() 等
 * 	  prev_{module}():       获取上一条记录的函数，如 prev_user()
 * 	  next_{module}():       获取下一条记录的函数，如 next_user()
 * 	  {module}_parent():     获取父记录信息的函数，如 category_parent()
 * 	  {module}_{ex-table}(): 获取从表记录的函数，如 post_user()
 * 6. 针对当前登录用户，ModPHP 同时注册了下面这些函数:
 *    get_me(): 包含当前登录用户所有信息的函数
 *    me_*():   与 user_* 字段对应的函数，如 me_id(), me_name(), me_nickname() 等
 */
return array(
	'user' => array( //用户表
		'user_id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY', //用户 ID
		'user_name' => 'VARCHAR(255) NOT NULL', //用户名
		'user_nickname' => 'VARCHAR(255) NOT NULL', //昵称
		'user_realname' => 'VARCHAR(255) NOT NULL', //真实姓名
		'user_password' => 'VARCHAR(255) NOT NULL', //密码
		'user_gender' => 'VARCHAR(255) NOT NULL', //性别
		'user_avatar' => 'VARCHAR(255) NOT NULL', //头像
		'user_identity' => 'VARCHAR(255) NOT NULL', //身份
		'user_company' => 'VARCHAR(255) NOT NULL', //所在单位
		'user_number' => 'VARCHAR(255) NOT NULL', //编号
		'user_email' => 'VARCHAR(255) NOT NULL', //电子邮箱，用于登录、显示和找回密码
		'user_tel' => 'VARCHAR(255) NOT NULL', //手机号码，用于登录和显示
		'user_desc' => 'VARCHAR(255) NOT NULL', //简介
		'user_protect' => 'VARCHAR(255) NOT NULL', //自定义保护字段，非当前用户或管理员获取用户信息时将被过滤
		'user_level' => 'INT(11) UNSIGNED DEFAULT 1', //用户等级，默认 1
		),
	'file' => array( //文件表
		'file_id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY', //文件 ID
		'file_name' => 'VARCHAR(255) NOT NULL', //文件名
		'file_src' => 'VARCHAR(255) NOT NULL', //源地址
		'file_desc' => 'VARCHAR(255) NOT NULL', //描述
		),
	'category' => array( //分类目录表
		'category_id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY', //目录 ID
		'category_name' => 'VARCHAR(255) NOT NULL', //名称
		'category_alias' => 'VARCHAR(255) NOT NULL', //别名
		'category_desc' => 'VARCHAR(255) NOT NULL', //描述
		'category_parent' => 'INT(11) UNSIGNED DEFAULT 0', //父目录 ID
	    'category_posts' => 'INT(11) UNSIGNED DEFAULT 0', //文章数量，系统自动设置
		'category_children' => 'INT(11) UNSIGNED DEFAULT 0', //子目录数量，当调用 Category::getTree() 方法时，将填充为一个多维数组
		),
	'post' => array( //文章表
		'post_id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY', //文章 ID
		'post_title' => 'VARCHAR(255) NOT NULL', //标题
		'post_content' => 'VARCHAR(10239) NOT NULL', //内容
		'post_thumbnail' => 'VARCHAR(255) NOT NULL', //特色图
	    'post_comments' => 'INT(11) UNSIGNED DEFAULT 0', //评论数，系统自动设置
		'post_time' => 'INT(11) UNSIGNED DEFAULT 0', //发表时间
		'post_link' => 'VARCHAR(255) NOT NULL', //自定义链接
		'category_id' => 'INT(11) UNSIGNED NOT NULL', //分类目录 ID
		'user_id' => 'INT(11) UNSIGNED NOT NULL', //用户 ID
		),
	'comment' => array( //评论表
		'comment_id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY', //评论 ID
		'comment_content' => 'VARCHAR(1023) NOT NULL', //评论内容
		'comment_time' => 'INT(11) UNSIGNED DEFAULT 0', //发表时间
		'comment_parent' => 'INT(11) UNSIGNED DEFAULT 0', //父评论 ID
	    'comment_replies' => 'INT(11) UNSIGNED DEFAULT 0', //回复数，系统自动设置
		'post_id' => 'INT(11) UNSIGNED NOT NULL', //文章 ID
		'user_id' => 'INT(11) UNSIGNED NOT NULL', //用户 ID
		),
	);