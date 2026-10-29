<?php
/** CLI-only local installer. Creates random untracked credentials; never public. */
if(PHP_SAPI!=='cli')exit(1);
$root=realpath(__DIR__.'/..');$runtime=$root.'/.local/wordpress';
$dbUser='muneeb_local';$dbPass=bin2hex(random_bytes(18));$adminPass=bin2hex(random_bytes(12));
$db=new mysqli('127.0.0.1','root','','',3308);$db->query('CREATE DATABASE IF NOT EXISTS muneeb_mart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
$db->query("CREATE USER IF NOT EXISTS '$dbUser'@'127.0.0.1' IDENTIFIED BY '$dbPass'");$db->query("ALTER USER '$dbUser'@'127.0.0.1' IDENTIFIED BY '$dbPass'");$db->query("GRANT ALL ON muneeb_mart.* TO '$dbUser'@'127.0.0.1'");
$config="<?php\ndefine('DB_NAME','muneeb_mart');\ndefine('DB_USER','$dbUser');\ndefine('DB_PASSWORD','$dbPass');\ndefine('DB_HOST','127.0.0.1:3308');\ndefine('DB_CHARSET','utf8mb4');\ndefine('DB_COLLATE','');\ndefine('WP_ENVIRONMENT_TYPE','development');\ndefine('WP_HOME','http://127.0.0.1:8088');\ndefine('WP_SITEURL','http://127.0.0.1:8088');\ndefine('WP_DEBUG',true);\ndefine('WP_DEBUG_LOG',true);\ndefine('WP_DEBUG_DISPLAY',false);\ndefine('DISALLOW_FILE_EDIT',true);\ndefine('DISABLE_WP_CRON',true);\ndefine('WP_MEMORY_LIMIT','256M');\n";
foreach(['AUTH_KEY','SECURE_AUTH_KEY','LOGGED_IN_KEY','NONCE_KEY','AUTH_SALT','SECURE_AUTH_SALT','LOGGED_IN_SALT','NONCE_SALT'] as $key)$config.="define('$key','".bin2hex(random_bytes(40))."');\n";
$config.="\$table_prefix='mm_';\nif(!defined('ABSPATH'))define('ABSPATH',__DIR__.'/');\nrequire_once ABSPATH.'wp-settings.php';\n";file_put_contents($runtime.'/wp-config.php',$config);
define('WP_INSTALLING',true);require $runtime.'/wp-load.php';require_once ABSPATH.'wp-admin/includes/upgrade.php';require_once ABSPATH.'wp-admin/includes/plugin.php';
if(!is_blog_installed()){wp_install('MUNEEB MART','muneeb_admin','admin@localhost.invalid',false,'',''.$adminPass);file_put_contents($root.'/.local/access.txt',"LOCAL DEVELOPMENT ONLY\nStore: http://127.0.0.1:8088\nAdmin: http://127.0.0.1:8088/wp-admin/\nUsername: muneeb_admin\nPassword: $adminPass\nDo not commit this file or expose this environment publicly.\n");}
$result=activate_plugin('woocommerce/woocommerce.php');if(is_wp_error($result))throw new Exception($result->get_error_message());
echo "WordPress installed. WooCommerce activated.\n";
