<?php
/** Loopback development router. Never deploy this file to public hosting. */
$pidFile=__DIR__.'/../.local/web-server.pid';if(@file_get_contents($pidFile)!==(string)getmypid())file_put_contents($pidFile,(string)getmypid());
$root=realpath(__DIR__.'/../.local/wordpress');$path=rawurldecode(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH));
if(preg_match('~(?:^|/)(?:\.|wp-config\.php|readme\.html|license\.txt)|\.(?:log|sql|ini|env|bak)$~i',$path)){http_response_code(404);exit;}
$file=realpath($root.$path);
if($file&&str_starts_with($file,$root.DIRECTORY_SEPARATOR)&&is_file($file)){
    return false;
}
if($file&&str_starts_with($file,$root.DIRECTORY_SEPARATOR)&&is_dir($file))return false;
require $root.'/index.php';
