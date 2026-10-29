import fs from 'node:fs';
import path from 'node:path';
const root=process.cwd(),runtime=path.join(root,'.local/wordpress');
const config=path.join(runtime,'wp-config.php');
let text=fs.readFileSync(config,'utf8').replace("define('ABSPATH',__DIR__.'/');","if(!defined('ABSPATH'))define('ABSPATH',__DIR__.'/');");
fs.writeFileSync(config,text);
const mu=path.join(runtime,'wp-content/mu-plugins');fs.mkdirSync(mu,{recursive:true});
fs.writeFileSync(path.join(mu,'local-offline.php'),`<?php
// Local runtime only: avoid external update/telemetry timeouts. Never packaged.
add_filter('pre_http_request',function($pre,$args,$url){$host=parse_url($url,PHP_URL_HOST);if(!in_array($host,['127.0.0.1','localhost'],true))return new WP_Error('local_offline','External HTTP is disabled in this local testing environment.');return $pre;},10,3);
add_filter('wp_mail',function($args){return $args;});
`);
console.log('Local configuration normalized. External HTTP disabled for local QA only.');
