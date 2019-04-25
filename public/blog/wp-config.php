<?php
/**
 * WordPress基础配置文件。
 *
 * 这个文件被安装程序用于自动生成wp-config.php配置文件，
 * 您可以不使用网站，您需要手动复制这个文件，
 * 并重命名为“wp-config.php”，然后填入相关信息。
 *
 * 本文件包含以下配置选项：
 *
 * * MySQL设置
 * * 密钥
 * * 数据库表名前缀
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/zh-cn:%E7%BC%96%E8%BE%91_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL 设置 - 具体信息来自您正在使用的主机 ** //
/** WordPress数据库的名称 */
define('DB_NAME', '1809_a');

/** MySQL数据库用户名 */
define('DB_USER', 'root');

/** MySQL数据库密码 */
define('DB_PASSWORD', '123456');

/** MySQL主机 */
define('DB_HOST', '127.0.0.1');

/** 创建数据表时默认的文字编码 */
define('DB_CHARSET', 'utf8mb4');

/** 数据库整理类型。如不确定请勿更改 */
define('DB_COLLATE', '');

/**#@+
 * 身份认证密钥与盐。
 *
 * 修改为任意独一无二的字串！
 * 或者直接访问{@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org密钥生成服务}
 * 任何修改都会导致所有cookies失效，所有用户将必须重新登录。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'nFC]g:OW|l{_/%<#8:U5{wk0G.&lXOdPn{4:t7rR^vWzy#AS16ei(dZiotq.@8pz');
define('SECURE_AUTH_KEY',  'H+.!GE<]u)=~$~mnf27t1m5{iA}?#_:,;(+yD|{AF*>[46t@./N[W>H!kNZ|g#eN');
define('LOGGED_IN_KEY',    'eMv@^KfAnfx r5@S-afGA~Af`LlNk8z( Vn$;dn/Jb+R9;!}b$] x5i$fB=pKXuC');
define('NONCE_KEY',        'h`;P>R!vbGaKR&J6kQH!jVJ93lnWJOJ/gJJ9~up;A96{*c7MdVY-Q*pp;z0ZlSZA');
define('AUTH_SALT',        'LI|`_`iEU{iwLI^c$y&I3 q|:{4/8:S7.hDWSVIIXAZ5#PDUf>l5zoJ-P>c6h$x^');
define('SECURE_AUTH_SALT', 'F;+a!%6QWsRR16{Tln5d?<F:,^JbkvaR`Vn)0Rb=plJzj;T+Ve577{?ZFRX-hI{6');
define('LOGGED_IN_SALT',   'v]AMj]0qh2C3i:AM-qV/I[~tC*0CXgU9C73^0?CI$<Q%5M[R*%~(8E~*5LxZjC/&');
define('NONCE_SALT',       'mFQ=)nj -_?5O.z_PGdF-bFHu(7@iHXEZ:+(!lMFwZ*~_ 9g4Qh]&d(NwdX;Ikd~');

/**#@-*/

/**
 * WordPress数据表前缀。
 *
 * 如果您有在同一数据库内安装多个WordPress的需求，请为每个WordPress设置
 * 不同的数据表前缀。前缀名只能为数字、字母加下划线。
 */
$table_prefix  = 'wp_';

/**
 * 开发者专用：WordPress调试模式。
 *
 * 将这个值改为true，WordPress将显示所有用于开发的提示。
 * 强烈建议插件开发者在开发环境中启用WP_DEBUG。
 *
 * 要获取其他能用于调试的信息，请访问Codex。
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* 好了！请不要再继续编辑。请保存本文件。使用愉快！ */

/** WordPress目录的绝对路径。 */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** 设置WordPress变量和包含文件。 */
require_once(ABSPATH . 'wp-settings.php');
