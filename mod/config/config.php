<?php
/**
 * 设置常规配置
 * 模块的特殊配置项:
 * 1. {module}.template: 模板文件，相对于模板目录
 * 2. {module}.staticURL: 伪静态 URL 地址格式，关键字用花括号 {} 标识
 * 3. {module}.keys.require: 新增记录时所必须提交的字段
 * 4. {module}.keys.filter: 更新记录时将会过滤的字段(不包含主键字段), 默认对管理员无效，除非在字段前加 *
 * 5. {module}.keys.serialize: 自动序列化数据的字段，在获取数据时也自动反序列化
 * 6. {module}.keys.search: 可用于搜索的字段，在调用 mod::search() 方法时将在设置的字段中查询结果
 */
return array(
	'mod' => array( //系统设置
		'installed' => false, //是否已安装
		'language' => 'zh_CN', //语言
		'timezone' => 'Asia/Shanghai', //时区
		'outputBuffering' => 2, //输出缓冲区大小，可设置为 1-4096(4 KB) 的值，0 则不限制，在 PHP 5.4.0 之前，1 等于 4096
		'websocketPort' => 8080, //WebSocket 监听端口
		'escapeTags' => '<script><style><iframe>', //过滤 HTML 标签
		'database' => array( //数据库设置
			'host' => 'localhost', //主机地址
			'name' => 'modphp', //数据库名称
			'port' => 3306, //连接端口
			'username' => 'root', //用户名
			'password' => '', //登录密码
			'prefix' => 'mod_', //数据表前缀
			),
		'session' => array( //Session 设置
			'name' => 'MODID', //名称
			'maxLifeTime' => 60*60*24*7, //生存期(分钟)
			'savePath' => 'tmp/', //保存路径
			),
		'template' => array(
			'savePath' => 'template/',
			'compiler' => array(
				'enable' => false, //启用编译器，false/0 不启用, true/1 调试模式，每次运行都重新编译，2 产品模式，使用已有的编译结果
				'extraTags' => array('import', 'redirect'), //额外的 HTML 语义标签
				'savePath' => 'tmp/', //编译文件保存路径
				'stripComment' => false, //移除注释
				)
			)
		),
	'site' => array( //网站设置
		'name' => 'ModPHP', //名称
		'URL' => '', //固定 URL 地址
		'home' => array( //首页设置(相对于模板目录)
			'template' => 'index.php', //模板文件
			'staticURL' => 'page/{page}.html', //伪静态地址
			),
		'errorPage' => array( //错误页面设置(相对于模板目录)
			403 => '403.php', //403 页面
			404 => '404.php', //404 页面
			500 => '500.php', //500 页面
			),
		'maintenance' => array( //维护页面设置
			'pages' => '', //正在维护中的页面，多个页面用 | 或 , 分隔
			'exception' => 'is_admin()', //例外条件，即 if() 的参数
			'report' => 'report_500()', //报告消息，可以使用 report_403/404/500() 函数
			)
		),
	'user' => array( //用户模块设置
		'template' => 'profile.php', //模板文件
		'staticURL' => 'profile/{user_id}.html', //伪静态地址
		'keys' => array( //字段设置
			'login' => 'user_name|user_email|user_tel', //用户登录字段, 当设置为多个字段时，前台可统一使用 user 作为参数
			'require' => 'user_name|user_password|user_level', //用户注册必需字段
			'filter' => 'user_name|user_level', //用户更新过滤字段
			'serialize' => 'user_protect', //用户自序列化字段
			),
		'name' => array( //用户名设置
			'minLength'=>2, //最小长度
			'maxLength'=>30, //最大长度
			),
		'password' => array( //字段设置
			'minLength'=>5, //最小长度
			'maxLength'=>18, //最大长度
			),
		'level' => array( //级别设置
			'admin' => 5, //管理员
			'editor' => 4, //编辑
			)
		),
	'file' => array( //文件模块设置
		'keys' => array( //字段设置
			'require' => 'file_name|file_src', //添加数据必需字段
			'filter' => 'file_src', //更新数据过滤字段
			),
		'upload' => array( //上传设置
			'savePath' => 'upload/', //保存路径
			'acceptTypes' => 'jpg|jpeg|png|gif|bmp', //接受类型(后缀)
			'maxSize' => 1024*2, //最大体积(单位 KB)
			'imageSizes' => '64|96|128', //自动添加图像尺寸(宽度, 单位: px)
			)
		),
	'category' => array( //分类目录模块设置
		'template' => 'category.php', //模板文件
		'staticURL' => '{category_name}/page/{page}.html', //伪静态地址
		'keys'=>array( //字段设置
			'require' => 'category_name', //添加数据必需字段
			'filter' => 'category_name', //更新数据过滤字段
			)
		),
	'post' => array( //文章模块设置
		'template' => 'single.php', //模板文件
		'staticURL' => '{category_name}/{post_id}.html', //伪静态地址
		'keys' => array( //字段设置
			'require' => 'post_title|post_content|post_time|category_id|user_id', //添加数据必需字段
			'filter' => 'post_time|user_id', //更新数据过滤字段
			'search' => 'post_title|post_content', //搜索字段
			)
		),
	'comment' => array( //评论模块设置
		'keys' => array( //字段设置
			'require' => 'comment_content|comment_time|post_id', //添加数据必需字段
			'filter' => 'comment_time|post_id|*comment_parent', //更新数据过滤字段
			)
		)
	);