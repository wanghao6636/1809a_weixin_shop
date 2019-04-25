
rdPress基础配置文件。
 *
 *  * 这个文件被安装程序用于自动生成wp-config.php配置文件，
 *   * 您可以不使用网站，您需要手动复制这个文件，
 *    * 并重命名为“wp-config.php”，然后填入相关信息。
 *     *
 *      * 本文件包含以下配置选项：
 *       *
 *        * * MySQL设置
 *         * * 密钥
 *          * * 数据库表名前缀
 *           * * ABSPATH
 *            *
 *             * @link https://codex.wordpress.org/zh-cn:%E7%BC%96%E8%BE%91_wp-config.php
 *              *
 *               * @package WordPress
 *                */

// ** MySQL 设置 - 具体信息来自您正在使用的主机 ** //
// /** WordPress数据库的名称 */
// define('DB_NAME', '1809_a');
//
// /** MySQL数据库用户名 */
// define('DB_USER', 'root');
//
// /** MySQL数据库密码 */
// define('DB_PASSWORD', '123456');
//
// /** MySQL主机 */
// define('DB_HOST', '127.0.0.1');
//
// /** 创建数据表时默认的文字编码 */
// define('DB_CHARSET', 'utf8mb4');
//
// /** 数据库整理类型。如不确定请勿更改 */
// define('DB_COLLATE', '');
//
// /**#@+
//  * 身份认证密钥与盐。
//   *
//    * 修改为任意独一无二的字串！
//     * 或者直接访问{@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org密钥生成服务}
//      * 任何修改都会导致所有cookies失效，所有用户将必须重新登录。
//       *
//        * @since 2.6.0
//         */
//         define('AUTH_KEY',         '{i:~K!c7nCud3]Y$W9$z%]t{qA+z@<8<<{8d+GBeAz;F&[~2o}+&8_SRZ0pT+;fV');
//         define('SECURE_AUTH_KEY',  'R*~k.+^xLZPZ5Fyh@u3*G~Xy8+UR2~km@*Bp}-?<Ym5bnEE@Dj1:&.</wL5&-iw7');
//         define('LOGGED_IN_KEY',    ';}Q-y?_`Ma3ww2Mq7t~[U48]k+2}#Uy?;eo@!UpW%wy8Ua-`}0Gu q+5103>B?Qi');
//         define('NONCE_KEY',        'O<:5dK5K,=tYji^ejPa(Djj2`)AF%$~nN3|7h)jRQ^F&&-X&w^78vNr;L6[C-F>2');
//         define('AUTH_SALT',        ')!(?&.vwhsZ&G }x8pxbEPZ]NcXcWWz;;p2QwUE3lZ9;/^y7;d9cA$A7{&w=ZvQb');
//         define('SECURE_AUTH_SALT', 'w:9Oe;h.&6GC8I]bsDW@iU5M@hFI_4]Nw4PH(-VLcG#lJ:efL}t?7 ZK8Ta]k+4o');
//         define('LOGGED_IN_SALT',   '!8rrY=poKA;c64`6Fuo`JgZ9U8B+&o`=Hf=R=>fethL8/yed:.BPo#{Tx>;hsWJc');
//         define('NONCE_SALT',       'kG:o|;W7/o9}B#Rur{nF%y>XXb&^228c,_m&jMw-2.(CKR3w0MpPPK4-]V1~BiOS');
//
//         /**#@-*/
//
//         /**
//          * WordPress数据表前缀。
//           *
//            * 如果您有在同一数据库内安装多个WordPress的需求，请为每个WordPress设置
//             * 不同的数据表前缀。前缀名只能为数字、字母加下划线。
//              */
//              $table_prefix  = 'wp_';
//
//              /**
//               * 开发者专用：WordPress调试模式。
//                *
//                 * 将这个值改为true，WordPress将显示所有用于开发的提示。
//                  * 强烈建议插件开发者在开发环境中启用WP_DEBUG。
//                   *
//                    * 要获取其他能用于调试的信息，请访问Codex。
//                     *
//                      * @link https://codex.wordpress.org/Debugging_in_WordPress
//                       */
//                       define('WP_DEBUG', false);
//
//                       /* 好了！请不要再继续编辑。请保存本文件。使用愉快！ */
//
//                       /** WordPress目录的绝对路径。 */
//                       if ( !defined('ABSPATH') )
//                       	define('ABSPATH', dirname(__FILE__) . '/');
//
//                       	/** 设置WordPress变量和包含文件。 */
//                       	require_once(ABSPATH . 'wp-settings.php');
//
